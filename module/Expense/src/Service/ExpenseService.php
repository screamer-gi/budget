<?php

namespace Expense\Service;

use Expense\Persistence\Repository\ExpenseCategoryRepository;
use Expense\Persistence\Repository\ExpenseRepository;
use Functional as F;
use Application\Filters\FiltersSetInterface;
use Common\Service\CommonServiceInterface;
use Common\Service\Parameters\ParametersInterface;

class ExpenseService implements CommonServiceInterface
{
    private $repo;
    private $categoryRepo;

    public function __construct(
        ExpenseRepository         $repo
    ,   ExpenseCategoryRepository $categoryRepo
    ) {
        $this->repo               = $repo;
        $this->categoryRepo       = $categoryRepo;
    }

    /**
     * Список
     * @param \Application\Filters\FiltersSetInterface $filters
     * @return array
     */
    public function getList(FiltersSetInterface $filters)
    {
        return $this->repo->getDetailedList($filters);
    }

    /**
     * Создать
     * @param $expense
     * @param array $data
     * @return mixed
     */
    public function create($expense, array $data)
    {
        $restIndex = false;
        $sum = 0;
        $empty = [];
        $restCategory = $data['category'];

        foreach ($expense->details as $detailIndex => $detail) {
            $detail->expense = $expense;
            if ($restCategory && $restCategory == $detail->category->id) {
                $restIndex = $detailIndex;
            } else {
                $detail->amount = $this->parse($detail->formula);
                if ($detail->amount === false) {
                    $detail->amount = 0;
                } else if ($detail->amount == 0) {
                    $empty[] = $detailIndex;
                } else {
                    $sum += $detail->amount;
                }
            }
        }

        if ($restIndex !== false) {
            $rest = $expense->amount - $sum;
            if ($rest != 0) {
                $expense->details[$restIndex]->amount = $expense->details[$restIndex]->formula = $rest;
            } else {
                $empty[] = $restIndex;
            }
        }

        foreach ($empty as $i) {
            $expense->details[$i]->expense = null;
            unset($expense->details[$i]);
        }

        $this->repo->persist($expense);
        return $expense;
    }

    /**
     * Редактировать
     *
     * @param $expense
     * @param array $data
     */
    public function update($expense, array $data)
    {
        $restIndex = false;
        $sum = 0;
        $empty = [];
        $restCategory = $data['category'];

        foreach ($expense->details as $detailIndex => $detail) {
            $detail->expense = $expense;
            if ($restCategory && $restCategory == $detail->category->id) {
                $restIndex = $detailIndex;
            } else {
                $detail->amount = $this->parse($detail->formula);
                if ($detail->amount === false) {
                    $detail->amount = 0;
                } else if ($detail->amount == 0) {
                    $empty[] = $detailIndex;
                } else {
                    $sum += $detail->amount;
                }
            }
        }

        if ($restIndex !== false) {
            $rest = $expense->amount - $sum;
            if ($rest != 0) {
                $expense->details[$restIndex]->amount = $expense->details[$restIndex]->formula = $rest;
            } else {
                $empty[] = $restIndex;
            }
        }

        foreach ($empty as $i) {
            $expense->details[$i]->expense = null;
            if ($expense->details[$i]->id) {
                $this->repo->remove($expense->details[$i]);
            }
            unset($expense->details[$i]);
        }
    }

    private function parse($formula)
    {
        if ($formula == '') {
            return 0;
        }
        if (!preg_match('/^[-+*\/()0-9\.,]+$/', $formula)) {
            return false;
        }
        return eval("return ($formula);");
    }

    /**
     * Удаление
     *
     * @param $id
     * @return void
     */
    public function delete($id)
    {
        $this->repo->removeById($id);
    }

    /**
     * Вычитывание записи по id
     * @param $id
     * @return mixed
     */
    public function read($id)
    {
        $expense = $this->repo->findById($id);
        return $expense;
    }

    public function detach($expense)
    {
        $this->repo->detach($expense);
    }
}