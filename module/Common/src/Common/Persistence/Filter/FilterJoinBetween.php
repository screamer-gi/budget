<?php
namespace Common\Persistence\Filter;

use Common\Exceptions\ArgumentEmptyException;
use Doctrine\ORM\QueryBuilder;

class FilterJoinBetween implements FilterInterface
{
    private $entityName;
    private $joinColumn;
    private $column;
    private $minParam;
    private $maxParam;

    public function __construct($entityName, $joinColumn, $column, $minParam, $maxParam)
    {
        if (empty($entityName))  throw new ArgumentEmptyException('$entityName');
        if (empty($joinColumn))   throw new ArgumentEmptyException('$joinColumn');
        if (empty($column))      throw new ArgumentEmptyException('$column');

        $this->entityName  = $entityName;
        $this->joinColumn  = $joinColumn;
        $this->column      = $column;
        $this->minParam    = $minParam;
        $this->maxParam    = $maxParam;
    }

    public function addCondition($value, QueryBuilder $qb)
    {
        $qb->join("{$this->entityName}.{$this->joinColumn}", $this->joinColumn);

        if (isset($value[$this->minParam])) {
            $qb->andWhere(
                $qb->expr()->gte("{$this->joinColumn}.{$this->column}", ':minParam')
            );
            $qb->setParameter(':minParam', $value[$this->minParam]);
            if (isset($value[$this->maxParam])) {
                $qb->andWhere(
                    $qb->expr()->lte("{$this->joinColumn}.{$this->column}", ':maxParam')
                );
                $qb->setParameter(':maxParam', $value[$this->maxParam]);
            }
        } else if (isset($value[$this->maxParam])) {
            $qb->andWhere(
                $qb->expr()->lte("{$this->joinColumn}.{$this->column}", ':maxParam')
            );
            $qb->setParameter(':maxParam', $value[$this->maxParam]);
        }
    }
}
