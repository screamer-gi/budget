<?php

namespace Common\Presentation\Zend\Form\Validator;

use Common\Exceptions\ArgumentInvalidException;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class DateTimeCompare extends AbstractValidator
{
    const OPERATION_GREATER        = 'greater'       ;
    const OPERATION_GREATER_EQUALS = 'greaterEquals' ;
    const OPERATION_LESSER         = 'less'          ;
    const OPERATION_LESSER_EQUALS  = 'lessEquals'    ;

    const TPL_NOT_GREATER          = 'errNotGreater'       ;
    const TPL_NOT_GREATER_EQUALS   = 'errNotGreaterEquals' ;
    const TPL_NOT_LESSER           = 'errNotLesser'        ;
    const TPL_NOT_LESSER_EQUALS    = 'errNotLesserEquals'  ;

    protected $messageVariables = [
        'compareTo'  => 'compareToString'
    ];

    protected $messageTemplates =
        [ self::TPL_NOT_GREATER        => "Указанная дата не больше %compareTo%"
        , self::TPL_NOT_GREATER_EQUALS => "Указанная дата не равна и не больше %compareTo%"
        , self::TPL_NOT_LESSER         => "Указанная дата не меньше %compareTo%"
        , self::TPL_NOT_LESSER_EQUALS  => "Указанная дата не равна и не меньше %compareTo%"
        ];

    private   $compareToFormat = 'Y-m-d';
    private   $compareTo;
    protected $compareToString;
    private   $operation;

    public function __construct(\DateTime $compareTo, $operation)
    {
        $availableOperations =
            [ static::OPERATION_GREATER
            , static::OPERATION_GREATER_EQUALS
            , static::OPERATION_LESSER
            , static::OPERATION_LESSER_EQUALS
            ];

        if (! in_array($operation, $availableOperations))
            throw new ArgumentInvalidException("Unknown operation `$operation`");

        $this->compareTo       = $compareTo;
        $this->compareToString = $compareTo->format($this->compareToFormat);
        $this->operation       = $operation;

        parent::__construct();
    }

    /**
     * @todo test all possible variants
     * @todo OPERATION_GREATER_EQUALS <- tested
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $date = $value instanceof \DateTime ? $value : \DateTime::createFromFormat('!' . $this->compareToFormat, $value);
        $diff = $this->compareTo->diff($date);

        if (in_array($this->operation, [static::OPERATION_GREATER, static::OPERATION_GREATER_EQUALS])) {
            $valid = $this->validateGreaterEquals($diff);
        } else {
            $valid = $this->validateLesserEquals($diff);
        }

        return $valid;
    }

    public function validateGreaterEquals(\DateInterval $diff)
    {
        if ($this->operation == static::OPERATION_GREATER) {
            if ($diff->invert != 1 && ! $this->isZeroDiff($diff)) {
                return true;
            } else {
                $this->error(static::TPL_NOT_GREATER);
                return false;
            }
        } else {
            if ($diff->invert != 1) {
                return true;
            } else {
                $this->error(static::TPL_NOT_GREATER_EQUALS);
                return false;
            }
        }
    }

    public function validateLesserEquals(\DateInterval $diff)
    {
        if ($this->operation == static::OPERATION_LESSER) {
            if ($diff->invert == 1) {
                return true;
            } else {
                $this->error(static::TPL_NOT_LESSER);
                return false;
            }
        } else {
            if ($diff->invert == 1 || $this->isZeroDiff($diff)) {
                return true;
            } else {
                $this->error(static::TPL_NOT_LESSER_EQUALS);
                return false;
            }
        }
    }

    private function isZeroDiff(\DateInterval $diff)
    {
        return $diff->y + $diff->m + $diff->d + $diff->h + $diff->i + $diff->s == 0;
    }

    public function setCompareToFormat($compareToFormat)
    {
        $this->compareToFormat = $compareToFormat;
    }
}