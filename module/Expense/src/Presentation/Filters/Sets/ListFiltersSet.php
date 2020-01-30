<?php

namespace Expense\Presentation\Filters\Sets;

use Application\Filters\AbstractFilterSet;
use Application\Filters\FilterDateInterval;
use Expense\Persistence\Entity\Expense;

class ListFiltersSet extends AbstractFilterSet
{
    protected function configureFilters()
    {
        // todo FilterMonthYear
        return [
            [['dateStart', 'dateEnd'], new FilterDateInterval($this->repository->getModelAlias() ?? Expense::class, 'date', 'dateStart', 'dateEnd', false)]
        ];
    }
}
