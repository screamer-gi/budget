<?php

namespace AnnualReport;

use Laminas\Mvc\Controller\AbstractActionController;

class Controller extends AbstractActionController
{
    /** @var Service */
    private $service;

    /** @var FilterForm */
    private $filterForm;

    public function __construct(
        Service $service,
        FilterForm $filterForm
    ) {
        $this->service = $service;
        $this->filterForm = $filterForm;
    }

    public function indexAction()
    {
        $filterForm = $this->filterForm;
        $filterForm->setData($this->params()->fromQuery());
        $result = $this->service->getAnnualReport($filterForm->get('year')->getValue());
        return [
            'result' => $result,
            'filters' => $filterForm,
            'months' => [1 => 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
        ];
    }
}
