<?php

namespace AnnualReport;

use Laminas\Filter\ToInt;
use Laminas\InputFilter\InputFilter;

class ListInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'     => 'year',
            'required' => true,
            'filters'  => array(
                array('name' => ToInt::class),
            ),
        ));
    }
}
