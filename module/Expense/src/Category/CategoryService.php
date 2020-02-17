<?php

namespace Expense\Category;

use Expense\Persistence\Entity\Category;
use Expense\Persistence\Repository\ExpenseCategoryRepository;

class CategoryService
{
    /** @var ExpenseCategoryRepository */
    private $repository;

    public function __construct(ExpenseCategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Category[]
     */
    public function getOrderedCategories(): array
    {
        $categories = $this->repository->findAll();
        usort($categories, function (Category $category1, Category $category2) {
            return $category1->ordering <=> $category2->ordering;
        });
        return $categories;
    }
}
