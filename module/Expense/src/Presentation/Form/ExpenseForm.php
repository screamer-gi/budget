<?php
namespace Expense\Presentation\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Expense\Persistence\Entity\Category;
use Expense\Persistence\Entity\Place;
use Laminas\Form\Form;

class ExpenseForm extends Form
{
    protected $objectManager;

    public function __construct(
        ObjectManager $objectManager,
        DetailFieldset $detailFieldset,
        ExpenseValidator $expenseValidator,
        DoctrineHydrator $hydrator
    ) {
        parent::__construct('expense');
        $this->objectManager = $objectManager;
        $this->setInputFilter($expenseValidator)
             ->setHydrator($hydrator);

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name' => 'date',
            'type' => 'Date',
            'options' => [
                'label' => 'Date',
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'amount',
            'type' => 'Text',
            'options' => [
                'label' => 'Amount',
            ],
        ]);

        $this->add([
            'name' => 'place',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => [
                'label' => 'Place',
                'object_manager'     => $this->getObjectManager(),
                'target_class'       => Place::class,
                'property'           => 'location',
                'display_empty_item' => true,
                'empty_item_label'   => '---',
            ],
        ]);

        $this->add([
            'name' => 'cash',
            'type' => 'Checkbox',
            'options' => [
                'label' => 'Cash',
            ],
        ]);

        $this->add([
            'name' => 'comment',
            'type' => 'Text',
            'options' => [
                'label' => 'Comment',
            ],
        ]);

        $this->add([
            'name' => 'category',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => [
                'label' => 'Rest',
                'object_manager'     => $this->getObjectManager(),
                'target_class'       => Category::class,
                'property'           => 'title',
                'display_empty_item' => true,
                'empty_item_label'   => '---',
            ],
        ]);

        $this->add([
            'name' => 'details',
            'type' => 'Laminas\Form\Element\Collection',
            'options' => [
                'count' => 2,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => $detailFieldset
            ]
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => [
                'value' => 'Save',
                'id' => 'submitbutton',
            ],
        ]);
    }

    private function getObjectManager()
    {
        return $this->objectManager;
    }
}
