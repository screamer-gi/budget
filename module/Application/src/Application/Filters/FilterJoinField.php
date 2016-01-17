<?php

namespace Application\Filters;

use Common\Persistence\Filter\FilterInterface;
use Common\Exceptions\ArgumentEmptyException;
use Doctrine\ORM\QueryBuilder;

class FilterJoinField implements FilterInterface
{
    private $entityName = '';
    private $joinColumn = '';
    private $field      = '';

    /**
     * @param $entityName
     * @param $joinColumn
     * @param $field
     * @throws ArgumentEmptyException
     */
    public function __construct($entityName, $joinColumn, $field)
    {
        if (empty($entityName)) throw new ArgumentEmptyException('$entityName');
        if (empty($joinColumn)) throw new ArgumentEmptyException('$joinColumn');
        if (empty($field))      throw new ArgumentEmptyException('$field');

        $this->entityName = $entityName;
        $this->joinColumn = $joinColumn;
        $this->field      = $field;
    }

    public function addCondition($value, QueryBuilder $queryBuilder)
    {
        $queryBuilder->join("{$this->entityName}.{$this->joinColumn}", $this->joinColumn)
                     ->andWhere($queryBuilder->expr()->eq("{$this->joinColumn}.{$this->field}", ':joinFieldValue'))
                     ->setParameter(':joinFieldValue', $value);
    }
}