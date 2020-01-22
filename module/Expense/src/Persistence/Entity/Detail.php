<?php
namespace Expense\Persistence\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Детализация траты
 *
 * @ORM\Entity
 * @ORM\Table(name="expense_detail")
 */
class Detail
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\ManyToOne(targetEntity="Expense\Persistence\Entity\Expense", cascade={"ALL"})
     */
    public $expense;

    /**
     * @ORM\OneToOne(targetEntity="Expense\Persistence\Entity\Category")
     */
    public $category;

    /**
     * @ORM\Column(type="string")
     */
    public $formula;

    /**
     * @ORM\Column(type="float")
     */
    public $amount;
}