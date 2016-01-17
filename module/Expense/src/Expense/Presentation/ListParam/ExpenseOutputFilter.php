<?php
namespace Expense\Presentation\ListParam;


use Zend\Filter\Exception;
use Zend\Filter\FilterInterface;
use Functional as F;

class ExpenseOutputFilter implements FilterInterface {

    public function filter($r)
    {
        return [
            'id'       => $r->id
        ,   'date'     => $r->date
        ,   'amount'   => $r->amount
        ,   'location' => $r->place ? $r->place->location : ''
        ,   'cash'     => $r->cash
        ,   'comment'  => $r->comment
        ,   'detail'   => $d = array_combine(F\map($r->details, function($d) { return $d->category->id;})
                , F\pluck($r->details, 'amount'))
        ,   'disparity'=> abs($r->amount - F\sum($d)) >= .01
        ];
    }
}