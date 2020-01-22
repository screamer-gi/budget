<?php
namespace Application\Tests\Service;

use Application\DateService;

class DateServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testMustReturnTrueIsEarlier()
    {
        $dateService = new DateService();
        $toCompare   = "2013-07-01";
        $compareTo   = "2013-08-01";

        $this->assertTrue(
            $dateService->isEarlier(new \DateTime($toCompare), new \DateTime($compareTo)),
            "Date `{$toCompare}` must be earlier then `{$compareTo}`"
        );
    }
}