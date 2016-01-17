<?php
namespace Expense\Persistence\Repository;

use Common\Persistence\Repository\AbstractRepository;
use Expense\Persistence\Entity\Category;
use Expense\Persistence\Entity\Detail;
use Expense\Persistence\Entity\Expense;
use Functional as F;

class ExpenseCategoryRepository extends AbstractRepository
{
    public function hydrateExpense(Expense $expense)
    {
        $categoryIds = F\map($expense->details, function($detail) { return $detail->category->id; });
        $missingCategories = F\filter($this->findAll(), function($category) use ($categoryIds) {
            return !in_array($category->id, $categoryIds);
        });
        foreach ($missingCategories as $category) {
            $detail = new Detail();
            $detail->category = $category;
            $detail->expense  = $expense;
            $expense->details->add($detail);
        }
        return $expense;
    }

    public function getModelName()
    {
        return Category::class;
    }
}