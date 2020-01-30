<?php
namespace Expense\Presentation\Filters\Sets;

use Application\Filters\Set\CommonFactoryInterface;
use Expense\Persistence\Repository\ExpenseRepository;

class FiltersFactory implements CommonFactoryInterface
{
    /** @var ExpenseRepository */
    private $repository;

    public function __construct(ExpenseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getListSet(array $outerParams)
    {
        return new ListFiltersSet($outerParams, $this->repository);
    }
}
