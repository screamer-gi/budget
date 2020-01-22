<?php
namespace Expense\Presentation\ListParam;

use Laminas\InputFilter\InputFilter;

class ExpenseInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'     => 'month',
            'required' => false,
            'filters'  => array(
                array('name' => 'Int'),
            ),
        ));

        $this->add(array(
            'name'     => 'year',
            'required' => false,
            'filters'  => array(
                array('name' => 'Int'),
            ),
        ));
    }
} 