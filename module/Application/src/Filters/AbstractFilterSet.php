<?php

namespace Application\Filters;

use Common\Persistence\Repository\AbstractRepository;
use Doctrine\ORM\QueryBuilder;
use Common\Persistence\Filter\FilterInterface;

abstract class AbstractFilterSet implements FiltersSetInterface
{
    private $startParam = '';
    private $limitParam = '';

    /** @var array */
    protected $outerParams;

    /** @var AbstractRepository */
    protected $repository;

    public function __construct(array $outerParams = [], AbstractRepository $repository = null)
    {
        $this->outerParams = $outerParams;
        $this->repository = $repository;
    }

    public function applyFilters(QueryBuilder $queryBuilder)
    {
        $filtersMap = $this->configureFilters();

        foreach ($filtersMap as $paramsFilterPair) {
            $this->checkParams($params = $paramsFilterPair[0]);
            $this->checkFilter($filter = $paramsFilterPair[1]);
            if (count($params) > 1) {
                $valueParams = [];
                foreach ($params as $param) {
                    if (isset($this->outerParams[$param]) && !empty($this->outerParams[$param])) {
                        $valueParams[$param] = $this->outerParams[$param];
                    }
                }
            } else {
                $valueParams = (isset($this->outerParams[$params[0]])
                               && !empty($this->outerParams[$params[0]])) ? $this->outerParams[$params[0]] : null;
            }
            if (!empty($valueParams)) {
                $filter->addCondition($valueParams, $queryBuilder);
            }
        }

        if ($this->limitParam && $this->startParam) {
            if (isset($this->outerParams[$this->limitParam]) && $this->outerParams[$this->limitParam]) {
                isset($this->outerParams[$this->startParam]) &&
                    $queryBuilder->setFirstResult(
                        isset($this->outerParams[$this->startParam]) ? (int)$this->outerParams[$this->startParam] : 0
                    );
                $queryBuilder->setMaxResults($this->outerParams[$this->limitParam]);
            }
        }
    }

    public function getLimit()
    {
        return isset($this->outerParams[$this->limitParam]) && $this->outerParams[$this->limitParam] ? (int) $this->outerParams[$this->limitParam] : null;
    }

    public function getStart()
    {
        return isset($this->outerParams[$this->startParam]) ? (int) $this->outerParams[$this->startParam] : null;
    }

    /**
     * @param string $startParam
     * @param string $limitParam
     * @return AbstractFilterSet
     */
    public function enablePaging($startParam = 'start', $limitParam = 'limit')
    {
        $this->startParam = $startParam;
        $this->limitParam = $limitParam;

        return $this;
    }

    /**
     * @return AbstractFilterSet
     */
    public function disablePaging()
    {
        $this->startParam = '';
        $this->limitParam = '';

        return $this;
    }

    private function checkParams(array $params)
    {
        if (count($params) == 0) throw new WrongConfigurationException("$params is not array");
        return true;
    }

    private function checkFilter(FilterInterface $filter)
    {
        //HINT: TypeHint used for checking
        return true;
    }

    protected abstract function configureFilters();
}
