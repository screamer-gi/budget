<?php
namespace Common\Persistence\Filter;

use Doctrine\ORM\QueryBuilder;

interface FilterInterface
{
    public function addCondition($value, QueryBuilder $qb);
}
