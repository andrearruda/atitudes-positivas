<?php

namespace Application\Fieldset;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Doctrine\ORM\EntityManager;

class ToFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function getInputFilterSpecification()
    {
        return array(
            'name' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
                'validators' => array(
                    array('name' => 'NotEmpty'),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'max' => 25
                        ),
                    ),
                )
            ),
            'departament' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
                'validators' => array(
                    array('name' => 'NotEmpty'),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'max' => 40
                        ),
                    ),
                )
            ),
        );
    }

    public function __construct(EntityManager $entityManager)
    {
        parent::__construct('to');

        $this->add(array(
            'name' => 'name',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Nome',
                'label_attributes' => array (
                    'class' => 'control-label col-md-3',
                ),
                'twb-layout' => 'horizontal',
                'column-size' => 'md-9',
            ),
            'attributes' => array(
                'required' => true,
            ),
        ));

        $this->add(array(
            'name' => 'departament',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Departamento',
                'label_attributes' => array (
                    'class' => 'control-label col-md-3',
                ),
                'twb-layout' => 'horizontal',
                'column-size' => 'md-9',
            ),
            'attributes' => array(
                'required' => true,
            ),
        ));
    }
}