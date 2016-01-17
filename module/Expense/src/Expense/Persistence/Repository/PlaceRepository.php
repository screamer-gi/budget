<?php
namespace Expense\Persistence\Repository;

use Application\Filters\FiltersSetInterface;
use Common\Persistence\Repository\AbstractRepository;
use Expense\Persistence\Entity\Place;

class PlaceRepository extends AbstractRepository
{
    public function getDetailedList(FiltersSetInterface $filtersSet = null)
    {
        $m        = $this->getModelName();

        $qb = $this->entityManager->createQueryBuilder();

        $qb->select([$m])
            ->from($m, $m);

        //$filtersSet->applyFilters($qb);

        return $qb->getQuery()->getResult();
    }

    public function getModelName()
    {
        return Place::class;
    }
}