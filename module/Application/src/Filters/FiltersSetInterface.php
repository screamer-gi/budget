<?php
namespace Application\Filters;

use Doctrine\ORM\QueryBuilder;

interface FiltersSetInterface
{
    public function applyFilters(QueryBuilder $queryBuilder);
    public function getLimit();
    public function getStart();
    public function enablePaging();
    public function disablePaging();
}
