<?php
namespace User\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use FileStore\Service\FileService;
use Functional as F;
use Lib\Auth\Service\AuthInterface;
use Quote\Repository\QuoteRepository;

class SearchRepository
{
    /**
     * Менеджер сущностей
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager = null;
    private   $auth;
    private   $currentUser;

    public function __construct(EntityManager $entityManager, AuthInterface $auth)
    {
        $this->entityManager = $entityManager;
        $this->auth          = $auth;
        $this->currentUser         = $auth->getIdentity();
    }

    public function search($searchText)
    {
        if (! $searchText) return [];

        $entityColumnsMap =
            [ 'Request\Entity\Request'           => ['subject', 'text']
            , 'Report\Persistence\Entity\Report' => ['subject', 'text']
            , 'Quote\Entity\Quote'               => ['text']
            , 'Expense\Entity\Expense'           => ['name', 'description', 'contact']
            , 'News\Entity\News'                 => ['title', 'content', 'thesis']
            , 'Survey\Entity\Survey'             => ['question']
            , 'Task\Entity\Task'                 => ['subject', 'text']
            , 'Event\Entity\Event'               => ['subject', 'text']
            , 'FileStore\Entity\Document'        => ['name', 'description', 'comments']
            , 'FileStore\Entity\File'            => ['name', 'description']
            , 'FileStore\Entity\Folder'          => ['name']
            , 'Comment\Entity\Comment'           => ['text']
            , 'Staff\Entity\Employee'            => ['surname']
            ];

        return $this->searchAll($entityColumnsMap, $searchText);
    }

    private function searchAll(array $entityColumnsMap, $searchText)
    {
        $result = [];
        foreach ($entityColumnsMap as $entity => $columns) {
            $result = array_merge(
                $this->createQuery($entity, $columns, $searchText)->getQuery()->getArrayResult(),
                $result );
        }
        return $result;
    }

    private function createQuery($entityFullName, array $columns, $searchText)
    {
        $alias = $this->extractAlias($entityFullName);
        $nameColumn = $columns[0];

        $qb = $this->entityManager->createQueryBuilder();

        $qb->select(["$alias.id", "$alias.$nameColumn as name", "'$alias' as type"])
            ->from($entityFullName, $alias)
            ->where(
                call_user_func_array(
                    [$qb->expr(), "orX"],
                    F\map($columns, function ($column) use ($qb, $alias) {
                        return $qb->expr()->like("$alias.$column", ':likeValue');
                    })
                )
            );
        $qb->setParameter(':likeValue', '%' . $searchText . '%');
        if (null != ($callable = $this->getEntityMethod($entityFullName))) {
            call_user_func($callable, $qb, $alias);
        }
        return $qb;
    }

    private function extractAlias($entityFullName)
    {
        $parts = explode('\\', $entityFullName);
        return strtolower(array_pop($parts));
    }

    private function getEntityMethod($entityName)
    {
        $name = $this->extractAlias($entityName);
        return method_exists($this, $name) ? [$this, $name] : null;
    }

    protected function request(QueryBuilder $qb, $alias)
    {
        $x = $qb->expr();
        $qb->andWhere($x->orX(
            $x->eq($alias . '.executor', ':employeeId'),
            $x->eq($alias . '.author'  , ':employeeId')
        ));

        $qb->setParameter(':employeeId', $this->currentUser);
    }

    protected function report(QueryBuilder $qb, $alias)
    {
        $x = $qb->expr();
        $qb->andWhere($x->orX(
            $x->eq($alias . '.receiver', ':employeeId'),
            $x->eq($alias . '.author'  , ':employeeId')
        ))
        ->andWhere($x->eq("$alias.deleted", 0))
        ->setParameter(':employeeId', $this->currentUser);
    }

    protected function quote(QueryBuilder $qb, $alias)
    {
        $x = $qb->expr();
        $qb->andWhere($x->eq("$alias.status", ':status'))
           ->setParameter(':status', QuoteRepository::STATUS_PUBLISHED);
    }

    protected function partner(QueryBuilder $qb, $alias)
    {
        $qb->andWhere($qb->expr()->orX(
            $qb->expr()->eq("$alias.author", $this->currentUser)
        ,   $qb->expr()->eq("$alias.shared", 1)
        ));
    }

    protected function news(QueryBuilder $qb, $alias)
    {
        $qb->andWhere($qb->expr()->isNull("{$alias}.version"))
            ->leftJoin("{$alias}.seers", 'seer', 'WITH', $qb->expr()->eq('seer', $this->currentUser))
            ->andWhere($qb->expr()->orX(
                $qb->expr()->isNotNull('seer'),
                $qb->expr()->eq("{$alias}.seers_type", $qb->expr()->literal('all')),
                $qb->expr()->eq("{$alias}.author", $this->currentUser)
            ));
    }

    protected function survey(QueryBuilder $qb, $alias)
    {
        $qb
        ->leftJoin("$alias.seers", 'seer')
        ->andWhere($qb->expr()->orX(
            $qb->expr()->eq("$alias.author", ':currentUserId'),
            $qb->expr()->eq("$alias.show_result_for", $qb->expr()->literal("all")),
            $qb->expr()->andX(
                $qb->expr()->eq("$alias.show_result_for", $qb->expr()->literal("selected")),
                $qb->expr()->eq('seer', ':currentUserId')
            )
        ))
        ->setParameter(':currentUserId' , $this->currentUser);
    }

    protected function task(QueryBuilder $qb, $alias)
    {
        $x = $qb->expr();
        $qb->andWhere($x->orX(
            $x->eq($alias . '.executor', ':employeeId'),
            $x->eq($alias . '.author'  , ':employeeId')
        ))
        ->andWhere($x->eq("$alias.deleted", 0))
        ->setParameter(':employeeId', $this->currentUser);
    }

    protected function event(QueryBuilder $qb, $alias)
    {
        $qb ->andWhere($qb->expr()->eq("$alias.deleted", 0))
            ->leftJoin("$alias.participants", 'participant')
            ->andWhere($qb->expr()->orX(
                $qb->expr()->eq("$alias.author", ':currentUserId'),
                $qb->expr()->eq('participant', ':currentUserId')
            ))
            ->groupBy("$alias.id")
            ->setParameter(':currentUserId', $this->currentUser);
    }

    protected function document(QueryBuilder $qb, $alias)
    {
        $x  = $qb->expr();
        $qb ->andWhere($x->eq("$alias.hidden", 0))
            ->leftJoin("$alias.employee", 'employee')
            ->andWhere($x->orX(
                $x->eq("$alias.author", ':currentUserId'),
                $x->eq('employee', ':currentUserId')
            ))
            ->setParameter(':currentUserId', $this->currentUser);
    }

    protected function file(QueryBuilder $qb, $alias)
    {
        $x  = $qb->expr();
        $qb ->andWhere($x->neq("$alias.category", $x->literal(FileService::FILE_CATEGORY_DOCS)))
            ->leftJoin("$alias.employee", 'employee')
            ->andWhere($x->orX(
                $x->eq("$alias.author", ':currentUserId'),
                $x->eq('employee', ':currentUserId')
            ))
            ->setParameter(':currentUserId', $this->currentUser);
    }

    protected function folder(QueryBuilder $qb, $alias)
    {
        $x  = $qb->expr();
        $qb ->andWhere($x->eq("$alias.hidden", 0));
    }

    protected function employee(QueryBuilder $qb, $alias)
    {
        $x  = $qb->expr();
        $qb ->andWhere($x->eq("$alias.enabled", 1))
            ->leftJoin("$alias.visibleEmployees", 'visibleEmployee')
            ->andWhere($x->orX(
                $qb->expr()->isNull('visibleEmployee'),
                $x->eq('visibleEmployee', ':currentUserId')
            ))
            ->setParameter(':currentUserId', $this->currentUser);
    }

    protected function comment(QueryBuilder $qb, $alias)
    {
        $qb ->join("$alias.event", 'event');
        $alias = 'event';
        $qb ->andWhere($qb->expr()->eq("$alias.deleted", 0))
            ->leftJoin("$alias.participants", 'participant')
            ->andWhere($qb->expr()->orX(
                $qb->expr()->eq("$alias.author", ':currentUserId'),
                $qb->expr()->eq('participant', ':currentUserId')
            ))
            ->setParameter(':currentUserId', $this->currentUser);
    }
}