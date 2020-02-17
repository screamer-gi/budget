<?php

namespace AnnualReport;

use Laminas\Form\Form;

class FilterForm extends Form
{
    public function __construct()
    {
        parent::__construct('annual-report-filter');
        $this->setInputFilter(new ListInputFilter());
        $this->setAttribute('method', 'GET');

        $years = range(2014, date('Y')); //todo 2014 to global config
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
