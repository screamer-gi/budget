<?php
namespace Common\Service;

use Application\Filters\FiltersSetInterface;

interface CommonServiceInterface extends CreateDeleteServiceInterface, ListServiceInterface
{
    /**
     * Вычитывание записи по id
     * @param $id
     * @return mixed
     */
    public function read($id);

    /**
     * Обновление записи
     * @param $entity
     * @param array $data
     */
    public function update($entity, array $data);
}