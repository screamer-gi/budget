<?php
namespace Application\Filters;

use Common\Persistence\Filter\FilterInterface;
use Common\Exceptions\ArgumentEmptyException;
use Doctrine\ORM\QueryBuilder;

class FilterColumnsLike implements FilterInterface
{
    private $entityName    = '';
    private $searchColumns = [];

    public function __construct($entityName, array $columnsToSearch)
    {
        if (empty($entityName))      new ArgumentEmptyException('$entityName');
        if (empty($columnsToSearch)) new ArgumentEmptyException('$columnsToSearch');

        $this->entityName    = $entityName;
        $this->searchColumns = $columnsToSearch;
    }

    public function addCondition($value, QueryBuilder $queryBuilder)
    {
        $queryBuilder->andWhere(
            call_user_func_array(
                [$queryBuilder->expr(), "orX"],
                array_map(function ($element) use ($queryBuilder) {
                    return $queryBuilder->expr()->like("{$this->entityName}.{$element}", ':likeValue');
                }, $this->searchColumns)
            )
        );
        $queryBuilder->setParameter(':likeValue', '%' . $value . '%');
    }
}