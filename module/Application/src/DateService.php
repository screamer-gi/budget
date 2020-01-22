<?php
namespace Application;

class DateService
{
    public function isEarlier(\DateTime $toCompare, \DateTime $compareTo)
    {
        $diff = $compareTo->diff($toCompare);

        return $diff->invert == 1 ? true : false;
    }

    public function isLater(\DateTime $toCompare, \DateTime $compareTo)
    {
        $diff = $compareTo->diff($toCompare);

        return $diff->invert == 1 ? false : true;
    }
}