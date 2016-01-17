<?php

namespace Application\Filters;

use Common\Persistence\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class FilterMonthYear implements FilterInterface {

    public function addCondition($value, QueryBuilder $qb)
    {
        // TODO: Implement addCondition() method.
    }
}