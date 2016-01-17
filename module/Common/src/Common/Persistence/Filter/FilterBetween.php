<?php
namespace Common\Persistence\Filter;

use Common\Exceptions\ArgumentEmptyException;
use Doctrine\ORM\QueryBuilder;

class FilterBetween implements FilterInterface
{
    private $entityName;
    private $column;
    private $minParam;
    private $maxParam;

    public function __construct($entityName, $column, $minParam, $maxParam)
    {
        if (empty($entityName))     throw new ArgumentEmptyException('$entityName');
        if (empty($column))         throw new ArgumentEmptyException('$column');

        $this->entityName  = $entityName;
        $this->column      = $column;
        $this->minParam    = $minParam;
        $this->maxParam    = $maxParam;
    }

    public function addCondition($value, QueryBuilder $qb)
    {
        if (isset($value[$this->minParam])) {
            $qb->andWhere(
                $qb->expr()->gte("{$this->entityName}.{$this->column}", ':minParam')
            );
            $qb->setParameter(':minParam', $value[$this->minParam]);
            if (isset($value[$this->maxParam])) {
                $qb->andWhere(
                    $qb->expr()->lte("{$this->entityName}.{$this->column}", ':maxParam')
                );
                $qb->setParameter(':maxParam', $value[$this->maxParam]);
            }
        } else if (isset($value[$this->maxParam])) {
            $qb->andWhere(
                $qb->expr()->lte("{$this->entityName}.{$this->column}", ':maxParam')
            );
            $qb->setParameter(':maxParam', $value[$this->maxParam]);
        }
    }
}
