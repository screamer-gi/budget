<?php
namespace Common\Persistence\Filter;

use Common\Exceptions\ArgumentEmptyException;
use Doctrine\ORM\QueryBuilder;

class FilterEq implements FilterInterface
{
    private $entityName = '';
    private $field      = '';

    /**
     * @param $entityName
     * @param $field
     * @throws ArgumentEmptyException
     */
    public function __construct($entityName, $field)
    {
        if (empty($entityName)) throw new ArgumentEmptyException('$entityName');
        if (empty($field))      throw new ArgumentEmptyException('$field');

        $this->entityName = $entityName;
        $this->field      = $field;
    }

    public function addCondition($value, QueryBuilder $qb)
    {
        $qb->andWhere($qb->expr()->eq("{$this->entityName}.{$this->field}", $qb->expr()->literal($value)));
    }
}