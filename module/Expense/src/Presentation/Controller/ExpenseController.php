<?php
namespace Expense\Presentation\Controller;

use Doctrine\ORM\EntityManager;
use Expense\Persistence\Entity\Expense;
use Expense\Persistence\Repository\ExpenseCategoryRepository;
use Expense\Presentation\Filters\Sets\FiltersFactory;
use Expense\Presentation\ListParam\ExpenseOutputFilter;
use Expense\Presentation\ListParam\FilterForm;
use Expense\Service\ExpenseService;
use Functional as F;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ExpenseController extends AbstractActionController
{
    /** @var ExpenseService */
    private $expenseService;

    /** @var ExpenseCategoryRepository */
    private $categoryRepo;

    /** @var EntityManager */
    private $em;

    /** @var FiltersFactory */
    private $filterFactory;

    /** @var ExpenseOutputFilter */
    private $outFilter;

    /** @var FilterForm */
    private $filterForm;

    private $form;

    public function __construct(
        ExpenseService $service,
        ExpenseCategoryRepository $categoryRepository,
        EntityManager $em,
        FiltersFactory $filterFactory,
        ExpenseOutputFilter $outFilter,
        FilterForm $filterForm
    ) {
        $this->expenseService = $service;
        $this->categoryRepo = $categoryRepository;
        $this->em = $em;
        $this->filterFactory = $filterFactory;
        $this->outFilter = $outFilter;
        $this->filterForm = $filterForm;
    }

    public function indexAction()
    {
        $filterForm = $this->filterForm;
        $filterForm->setData($this->params()->fromQuery());
        $m = $filterForm->get('month')->getValue();
        $y = $filterForm->get('year')->getValue();

        $table = [];
        $categories = $this->categoryRepo->findAll();
        $categoryIds = F\pluck($categories, 'id');
        $categories = array_combine($categoryIds, F\pluck($categories, 'title'));
        $categorySummary = array_fill_keys($categoryIds, 0.0);
        $count = 0;
        $summary = 0.0;

        $lastDay = date('t', strtotime("$y-$m-01"));
        for ($i = 1; $i <= $lastDay; $i++) {
            $table[sprintf('%02d.%02d.%04d', $i, $m, $y)] = false;
        }

        $filters = $this->filterFactory->getListSet([
            'dateStart' => "$y-$m-01",
            'dateEnd'   => "$y-$m-$lastDay",
        ]);

        foreach ($this->expenseService->getList($filters) as $r) {
            $table[$r->date->format('d.m.Y')][] = $this->outFilter->filter($r);
            F\map($r->details, function($d) use (&$categorySummary) {
                $categorySummary[$d->category->id] += $d->amount;
            });
            $summary += $r->amount;
            $count++;
        }

        return (new ViewModel([
            'table'           => $table,
            'categories'      => $categories,
            'categorySummary' => $categorySummary,
            'summary'         => $summary,
            'count'           => $count,
            'filters'         => $filterForm,
            'weekdays'        => ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
        ]))->setTemplate('expense/index');
    }

    public function addAction()
    {
        $expense = $this->categoryRepo->hydrateExpense(new Expense());
        $date = $this->params()->fromQuery('date');
        $expense->date = \DateTime::createFromFormat('d.m.Y', $date);

        $form = $this->form;
        $form->get('submit')->setValue('Add');
        $form->bind($expense);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $this->expenseService->create($expense, $form->getInputFilter()->getValues());
                $this->em->flush();///////////////////////////////
                return $this->redirect()->toRoute('expense', [], ['query' => ['month' => $expense->date->format('m'), 'year' => $expense->date->format('Y')]]);
            }
            //else {Debug::dump( $form->getMessages()); Debug::dump($form->getData());die('false');}
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $expense = $this->expenseService->read($id);
        if (null === $expense) {
            return $this->redirect()->toRoute('expense');
        }
        $this->categoryRepo->hydrateExpense($expense);

        $form = $this->form;
        $form->get('submit')->setValue('Update');
        $form->bind($expense);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->expenseService->update($expense, $form->getInputFilter()->getValues());
                $this->em->flush();
                return $this->redirect()->toRoute('expense', [], ['query' => ['month' => $expense->date->format('m'), 'year' => $expense->date->format('Y')]]);
            }
        }

        $this->expenseService->detach($expense);
        return [
            'form' => $form,
            'id'   => $id,
        ];
    }
}
