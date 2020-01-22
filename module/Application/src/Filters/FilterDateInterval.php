<?php
namespace Application\Filters;

use Common\Persistence\Filter\FilterInterface;
use Common\Exceptions\ArgumentEmptyException;
use Doctrine\ORM\QueryBuilder;

class FilterDateInterval implements FilterInterface
{
    private $entityName;
    private $column;
    private $dateStartParam;
    private $dateEndParam;
    private $includeTime;

    public function __construct($entityName, $column, $dateStartParam, $dateEndParam, $time = true)
    {
        if (empty($entityName))     throw new ArgumentEmptyException('$entityName');
        if (empty($column))         throw new ArgumentEmptyException('$column');
        if (empty($dateStartParam)) throw new ArgumentEmptyException('$dateStartParam');
        if (empty($dateEndParam))   throw new ArgumentEmptyException('$dateEndParam');

        $this->entityName      = $entityName;
        $this->column          = $column;
        $this->dateStartParam  = $dateStartParam;
        $this->dateEndParam    = $dateEndParam;
        $this->includeTime     = $time;
    }

    public function addCondition($value, QueryBuilder $qb)
    {
        if (isset($value[$this->dateStartParam])) {
            $qb->andWhere(
                $qb->expr()->gte("{$this->entityName}.{$this->column}", ':dateStart')
            );
            $qb->setParameter(':dateStart', $value[$this->dateStartParam]);
            if (isset($value[$this->dateEndParam])) {
                $qb->andWhere(
                    $qb->expr()->lte("{$this->entityName}.{$this->column}", ':dateEnd')
                );
                $qb->setParameter(':dateEnd', $value[$this->dateEndParam] . ($this->includeTime ? '00:00:00' : ''));
            }
        } else if (isset($value[$this->dateEndParam])) {
            $qb->andWhere(
                $qb->expr()->lte("{$this->entityName}.{$this->column}", ':dateStart')
            );
            $qb->setParameter(':dateStart', $value[$this->dateEndParam] . ($this->includeTime ? '23:59:59' : ''));
        }
    }
}
