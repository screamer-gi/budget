<?php
namespace Expense\Persistence\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Категория траты
 *
 * @ORM\Entity
 * @ORM\Table(name="expense_category")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     */
    public $title;


    public function __construct()
    {
    }

    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Magic getter to expose protected properties.
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

        return null;
    }

    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        $vars = get_object_vars($this);
        array_walk($vars, function (&$value, $property) { $value = $this->__get($property); });
        return $vars;
    }

    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function populate(array $data)
    {
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }
}