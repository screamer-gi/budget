<?php

namespace Expense\Persistence\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Трата
 *
 * @ORM\Entity
 * @ORM\Table(name="expense")
 */
class Expense
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="date")
     */
    public $date;

    /**
     * @ORM\Column(type="float")
     */
    public $amount;

    /**
     * @ORM\OneToOne(targetEntity="Expense\Persistence\Entity\Place")
     */
    public $place;

    /**
     * @ORM\Column(type="smallint")
     */
    public $cash;

    /**
     * @ORM\Column(type="string")
     */
    public $comment;

    /**
     * @ORM\OneToMany(targetEntity="Expense\Persistence\Entity\Detail", mappedBy="expense", cascade={"ALL"})
     */
    public $details;

    public function __construct()
    {
        $this->details = new ArrayCollection();
    }

    public function addDetails(Collection $collection)
    {
        foreach ($collection as $detail) {
            $detail->expense = $this;
            $this->details->add($detail);
        }
    }

    public function removeDetails(Collection $collection)
    {
        foreach ($collection as $detail) {
            $detail->expense = null;
            $this->details->removeElement($detail);
        }
    }
}