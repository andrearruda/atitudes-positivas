<?php
namespace Application\Fieldset;

use Doctrine\ORM\EntityManager;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class StaffFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function getInputFilterSpecification()
    {
        return array(
            'id_unit' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
                'validators' => array(
                    array('name' => 'NotEmpty'),
                    array('name' => 'Int'),
                )
            ),
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
            'email' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
                'validators' => array(
                    array('name' => 'EmailAddress'),
                    array('name' => 'NotEmpty'),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'max' => 255
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
        parent::__construct('staff');

        $this->add(array(
            'name' => 'id_unit',
            'type'    => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Unidade',
                'label_attributes' => array (
                    'class' => 'control-label col-md-3',
                ),
                'twb-layout' => 'horizontal',
                'column-size' => 'md-9',
                'object_manager' => $entityManager,
                'target_class'   => 'Application\Entity\Unit',
                'property'       => 'name',
                'empty_option'   => 'Escolha uma unidade'
            ),
            'attributes' => array(
                'required' => true,
            ),
        ));

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
            'name' => 'email',
            'type' => 'Zend\Form\Element\Email',
            'options' => array(
                'label' => 'E-mail',
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