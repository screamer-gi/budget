<?php
namespace Common\Service;

interface CreateDeleteServiceInterface
{
    /**
     * Создание записи
     * @param $entity
     * @param array $data
     * @return mixed
     */
    public function create  ($entity, array $data);

    /**
     * Удаление записи
     * @param $fieldId
     * @return mixed
     */
    public function delete  ($fieldId);
}