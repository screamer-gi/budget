<?php
namespace Expense\Presentation\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Laminas\Hydrator\DoctrineObject;
use Expense\Persistence\Entity\Category;
use Expense\Persistence\Entity\Detail;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;

class DetailFieldset extends Fieldset implements InputFilterProviderInterface
{
    function __construct(ObjectManager $objectManager, DoctrineObject $hydrator)
    {
        parent::__construct('detail');
        $this->setHydrator($hydrator);
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
                'object_manager'     => $objectManager,
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

    public function getInputFilterSpecification()
    {
        return [
            'id' => [
                'required' => false,
            ],
            'category' => [
                'required' => true,
            ],
            'formula' => [
                'required' => false,
                'filters' => [
                    ['name' => 'StringTrim'],
                ],
            ],
        ];
    }
}
