<?php
namespace Common\Service;

use Application\Filters\FiltersSetInterface;

interface ListServiceInterface
{
    /**
     * Получение списка
     * @param FiltersSetInterface $filtersSet
     * @return mixed
     */
    public function getList (FiltersSetInterface $filtersSet);
}