<?php
namespace Application\Fieldset;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class MessageFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function getInputFilterSpecification()
    {
        return array(
            'description' => array(
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
                            'max' => 140
                        ),
                    ),
                )
            ),
            'image' => array(
                'required' => true,
                'filters' => array(
                    array(
                        'name' => '\Zend\Filter\File\RenameUpload',
                        'options' => array(
                            'target' => __DIR__ . '/../../../../../data/uploads/original/img',
                            'use_upload_extension' => true,
                            'overwrite' => true,
                            'randomize' => true
                        )
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\File\Extension',
                        'options' => array(
                            'extension' => 'jpg,jpeg,png',
                        )
                    ),
                    array(
                        'name' => 'Zend\Validator\File\MimeType',
                        'options' => array(
                            'mimeType' => 'image/jpeg,image/jpg,image/png,image/x-png',
                        )
                    ),
                    array(
                        'name' => 'Zend\Validator\File\Size',
                        'options' => array(
                            'max' => 6291456,
                        )
                    ),
                )
            )
        );
    }

    public function __construct()
    {
        parent::__construct('message');
        $this->setAttribute('enctype','multipart/form-data');

        $this->add(array(
            'name' => 'description',
            'type' => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => 'Descrição',
                'label_attributes' => array (
                    'class' => 'control-label col-md-3',
                ),
                'twb-layout' => 'horizontal',
                'column-size' => 'md-9',
            ),
            'attributes' => array(
                'required' => true,
                'rows' => 5
            ),
        ));

        $this->add(array(
            'name' => 'image',
            'type' => 'Zend\Form\Element\File',
            'options' => array(
                'label' => 'Imagem',
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