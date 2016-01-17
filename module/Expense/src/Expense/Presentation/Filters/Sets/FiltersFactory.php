<?php
namespace Expense\Presentation\Filters\Sets;

use Application\Filters\Set\CommonFactoryInterface;

class FiltersFactory implements CommonFactoryInterface
{
    public function getListSet(array $outerParams)
    {
        return new ListFiltersSet($outerParams);
    }
}