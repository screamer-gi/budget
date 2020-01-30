<?php
namespace Common\Persistence\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Functional as F;
use Application\Filters\FiltersSetInterface;
use Common\Exceptions\ArgumentEmptyException;
use Common\Exceptions\ArgumentNullException;
use Common\Exceptions\Db\RecordNotFound;

abstract class AbstractRepository extends EntityRepository
{
    /**
     * Менеджер сущностей
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager = null;

    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager, $entityManager->getClassMetadata($this->getModelName()));
        $this->entityManager = $entityManager;
    }

    public function findAll(FiltersSetInterface $filterSet = null)
    {
        return $this->findAllQuery($filterSet)->getQuery()->getResult();
    }

    protected function findAllQuery(FiltersSetInterface $filterSet = null)
    {
        $qb = $this->getQueryBuilder();
        if ($filterSet != null) {
            $filterSet->applyFilters($qb);
        }
        return $qb;
    }

    /**
     * Построение запроса поиска по критериям с применением фильтров и сортировкой
     * @param array $criteria
     * @param FiltersSetInterface $filterSet
     * @param array|null $orderBy
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function findByQuery(array $criteria, FiltersSetInterface $filterSet, $orderBy = null) {
        $qb = $this->findAllQuery($filterSet);
        $x  = $qb->expr();
        $modelName = $this->getModelName();

        foreach ($criteria as $name => $value) {
            if (is_array($value)) {
                $qb->andWhere($x->in("$modelName.$name", F\map($value, [$x, 'literal'])));
            } else {
                $qb->andWhere($x->eq("$modelName.$name", $value));
            }
        }
        if ($orderBy) {
            foreach ($orderBy as $field => $orientation) {
                $qb->addOrderBy($field, $orientation);
            }
        }
        return $qb;
    }

    /**
     * Поиск по критериям с применением фильтров и сортировкой
     * @param array $criteria
     * @param FiltersSetInterface $filterSet
     * @param array|null $orderBy
     * @return array
     */
    public function findByFilter(array $criteria, FiltersSetInterface $filterSet = null, $orderBy = null, $limit = null, $offset = null)
    {
        if ($filterSet) {
            return $this->findByQuery($criteria, $filterSet, $orderBy)->getQuery()->getResult();
        }

        return $this->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findBetweenAnd(array $columnValuesMap)
    {
        return $this->findBetween($columnValuesMap);
    }

    public function findBetweenOr(array $columnValuesMap)
    {
        return $this->findBetween($columnValuesMap, 'OR');
    }

    private function findBetween(array $columnValuesMap, $logicalOperator = 'AND')
    {
        if (empty($columnValuesMap)) throw new ArgumentEmptyException('$columnValuesMap');

        $qb        = $this->getQueryBuilder();
        $x         = $qb->expr();
        $modelName = $this->getModelName();
        foreach ($columnValuesMap as $column => $values) {
            if (is_array($values)) {
                list($lowerValue, $upperValue) = F\map($values, function($v) use ($x) {
                    //return $x->expr('CAST(' . $x->literal($v) . ' as DATE)');
                    return $x->literal($v);
                });
                if ($logicalOperator == 'AND') {
                    $qb->andWhere($x->andX(
                        $x->gte("$modelName.$column", $lowerValue),
                        $x->lte("$modelName.$column", $upperValue)
                    ));
                } else {
                    $qb->orWhere($x->andX(
                        $x->gte("$modelName.$column", $lowerValue),
                        $x->lte("$modelName.$column", $upperValue)
                    ));
                }
            } else {
                $qb->andWhere($qb->expr()->eq("$modelName.$column", $x->literal($values)));
            }
        }

        return $qb->getQuery()->getResult();
    }

    public function findInBetween($value, $lowerField = null, $upperField = null)
    {
        $qb        = $this->getQueryBuilder();
        $x         = $qb->expr();
        $modelName = $this->getModelName();

        if (is_array($value)) {

            foreach ($value as $columns) {
                if (count($columns) == 2) {
                    list($columnValue, $lowerField) = $columns;
                    $qb->andWhere($x->eq("$modelName.$lowerField", $x->literal($columnValue)));
                } else if (count($columns) == 3) {
                    list($columnValue, $lowerField, $upperField) = $columns;
                    $qb->andWhere($x->andX(
                        $x->gte("$modelName.$upperField", $x->literal($columnValue)),
                        $x->lte("$modelName.$lowerField", $x->literal($columnValue . ' 23:59:59'))
                    ));
                }
            }

        } else {

            $qb->where($x->andX(
                $x->gte("$modelName.$upperField", $x->literal($value)),
                $x->lte("$modelName.$lowerField", $x->literal($value . ' 23:59:59'))
            ));
        }

        return $qb->getQuery()->getResult();
    }

    public function findById($id)
    {
        return $this->find($id);
    }

    public function getOneBy(array $criteria)
    {
        if (empty($criteria)) throw new ArgumentEmptyException('$criteria');

        $entity = $this->findOneBy($criteria);

        if (!$entity) {
            throw new RecordNotFound(var_export($criteria, 1));
        }

        return $entity;
    }

    public function getById($id)
    {
        if (empty($id)) throw new ArgumentEmptyException('$id');

        $entity = $this->findById($id);

        if (!$entity) {
            throw new RecordNotFound($id);
        }
        return $entity;
    }

    public function remove($entity)
    {
        if ($entity == null) throw new ArgumentNullException('$entity');

        $this->entityManager->remove($entity);
    }

    /**
     * Удалить запись по ID
     * @param $id
     */
    public function removeById($id)
    {
        $this->entityManager->remove(
            $this->entityManager->getReference($this->getModelName(), $id)
        );
    }

    /**
     * Создать новую запись
     * @param array $data
     * @return mixed
     */
    public function newEntity(array $data = [])
    {
        $entityName = $this->getModelName();
        $entity     = new $entityName;

        if (! empty($data)) {
            $entity->populate($data);
        }

        $this->entityManager->persist($entity);
        return $entity;
    }

    public function persist($entity)
    {
        if ($entity == null) throw new ArgumentNullException('$entity');

        $this->entityManager->persist($entity);
    }

    public function merge($entity)
    {
        if ($entity == null) throw new ArgumentNullException('$entity');

        $this->entityManager->merge($entity);
    }

    public function detach($entity)
    {
        if ($entity == null) throw new ArgumentNullException('$entity');

        $this->entityManager->detach($entity);
    }

    public function flush()
    {
        $this->entityManager->flush();
    }

    /**
     * @param string|null $alias
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getQueryBuilder($alias = null)
    {
        return $this->createQueryBuilder($alias ?: $this->getModelAlias());
    }

    public function getRepository()
    {
        return $this;
    }

    public function getModelAlias(): string
    {
        return str_replace('\\', '_', $this->getModelName());
    }

    public abstract function getModelName();
}
