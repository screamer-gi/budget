<?php
namespace Expense\Presentation\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Expense\Persistence\Entity\Place;
use Zend\Form\Fieldset;

class ExpenseFieldset extends Fieldset implements ObjectManagerAwareInterface {
    protected $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('expense');
        $this->setObjectManager($objectManager);
        $this->setHydrator(new DoctrineHydrator($objectManager, false));
        $this->setObject(new Place());

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name' => 'date',
            'type' => 'Text',
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

        /*$this->add([
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'details',
            'options' => [
                'count' => 2,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => new DetailFieldset($this->getObjectManager())
            ]
        ]);*/

        $this->add([
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => [
                'value' => 'Save',
                'id' => 'submitbutton',
            ],
        ]);
    }

    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        return $this;
    }

    public function getObjectManager()
    {
        return $this->objectManager;
    }

}