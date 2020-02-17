<?php
namespace Expense\Persistence\Repository;

use Application\Filters\FiltersSetInterface;
use Common\Persistence\Repository\AbstractRepository;
use Expense\Persistence\Entity\Expense;

class ExpenseRepository extends AbstractRepository
{
    public function getDetailedList(FiltersSetInterface $filtersSet = null)
    {
        $model = $this->getModelName();
        $alias = $this->getModelAlias();

        $qb = $this->entityManager->createQueryBuilder();
        $qb ->select([$alias, 'd'])
            ->from($model, $alias)
            ->join("$alias.details", 'd');

        if ($filtersSet) {
            $filtersSet->applyFilters($qb);
        }
        //echo $qb->getQuery()->getSQL();
        //\Zend\Debug\Debug::dump($qb->getQuery()->getParameters());

        return $qb->getQuery()->getResult();
    }

    public function getModelName()
    {
        return Expense::class;
    }
}
