<?php
namespace Expense\Presentation\ListParam;

use Laminas\Form\Form;

class FilterForm extends Form {
    public function __construct()
    {
        parent::__construct('expense-filter');
        $this->setInputFilter(new ExpenseInputFilter());
        $this->setAttribute('method', 'GET');
            //->setHydrator(new DoctrineHydrator($objectManager, false));

        $this->add([
            'name' => 'month',
            'type' => 'Select',
            'options' => [
                'label' => 'Month',
                'value_options' => [
                    1  => 'Январь',
                    2  => 'Февраль',
                    3  => 'Март',
                    4  => 'Апрель',
                    5  => 'Май',
                    6  => 'Июнь',
                    7  => 'Июль',
                    8  => 'Август',
                    9  => 'Сентябрь',
                    10 => 'Октябрь',
                    11 => 'Ноябрь',
                    12 => 'Декабрь',
                ]
            ],
        ]);
        $this->get('month')->setValue(date('n'));

        $years = range(2014, date('Y'));
        $this->add([
            'name' => 'year',
            'type' => 'Select',
            'options' => [
                'label' => 'Year',
                'value_options' => array_combine($years, $years)
            ],
        ]);
        $this->get('year')->setValue(date('Y'));

        $this->add([
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => [
                'value' => 'Filter',
                'id' => 'submitbutton',
            ],
        ]);
    }
}
