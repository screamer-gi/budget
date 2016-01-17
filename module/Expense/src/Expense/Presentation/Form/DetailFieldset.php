<?php
namespace Expense\Presentation\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Expense\Persistence\Entity\Category;
use Expense\Persistence\Entity\Detail;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class DetailFieldset extends Fieldset implements InputFilterProviderInterface, ObjectManagerAwareInterface {
    protected $objectManager;

    function __construct(ObjectManager $objectManager)
    {
        parent::__construct('detail');
        $this->setObjectManager($objectManager)
             ->setHydrator(new DoctrineHydrator($objectManager, false));
        $this->setObject(new Detail());
        $this->setLabel('Detail');

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name' => 'category',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => [
                'label' => 'Category',
                'object_manager'     => $this->getObjectManager(),
                'target_class'       => Category::class,
                'property'           => 'title',
                'display_empty_item' => true,
                'empty_item_label'   => '---',
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'formula',
            'options' => [
                'label' => 'Formula'
            ],
        ]);
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'id' => [
                'required' => false
            ],
            'category' => [
                'required' => true,
            ],
            'formula' => [
                'required' => false,
                'filters' => [
                    ['name' => 'StringTrim']
                ]
            ]
        ];
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