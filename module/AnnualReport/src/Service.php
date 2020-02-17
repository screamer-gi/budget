<?php

namespace AnnualReport;

use Expense\Category\CategoryService;
use Expense\Persistence\Entity\Category;
use Expense\Presentation\Filters\Sets\FiltersFactory;
use Expense\Service\ExpenseService;

class Service
{
    /** @var ExpenseService */
    private $expenseService;

    /** @var FiltersFactory */
    private $filterFactory;

    /** @var CategoryService */
    private $categoryService;

    public function __construct(
        ExpenseService $expenseService,
        CategoryService $categoryService,
        FiltersFactory $filterFactory
    ) {
        $this->expenseService = $expenseService;
        $this->filterFactory = $filterFactory;
        $this->categoryService = $categoryService;
    }

    public function getAnnualReport(int $year): array
    {
        $categories = $this->categoryService->getOrderedCategories();
        $categoryIds = array_column($categories, 'id');
        $categories = array_combine($categoryIds, array_map(function (Category $category) {
            return [
                'title' => $category->getTitle(),
                'sum' => 0.0,
            ];
        }, $categories));

        $result = [
            'months' => array_fill_keys(range(1, 12), [
                'categories' => array_fill_keys($categoryIds, 0.0),
                'sum' => 0.0,
            ]),
            'categories' => $categories,
            'sum' => 0.0,
        ];

        $filters = $this->filterFactory->getListSet([
            'dateStart' => "$year-01-01",
            'dateEnd'   => "$year-12-31",
        ]);

        foreach ($this->expenseService->getList($filters) as $r) {
            $month = (int)$r->date->format('m');
            $result['months'][$month]['sum'] += $r->amount;
            $result['sum'] += $r->amount;
            foreach ($r->details as $detail) {
                $result['months'][$month]['categories'][$detail->category->id] += $detail->amount;
                $result['categories'][$detail->category->id]['sum'] += $detail->amount;
            }
        }

        return $result;
    }
}
