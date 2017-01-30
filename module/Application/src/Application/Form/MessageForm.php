<?php
namespace Application\Form;

use Application\Fieldset\ToFieldset;
use Doctrine\ORM\EntityManager;
use Zend\Form\Form;
use Application\Fieldset\StaffFieldset;

class MessageForm extends Form
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct('form_message');

        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf'
        ));

        $fieldset_staff = new StaffFieldset($entityManager);
        $fieldset_staff->setLabel('Seus Dados');
        $fieldset_staff->setName('fieldset_staff');
        $this->add($fieldset_staff);

        $fieldset_to = new ToFieldset($entityManager);
        $fieldset_to->setLabel('Para');
        $fieldset_to->setName('fieldset_to');
        $this->add($fieldset_to);

        $this->add(array(
            'type' => 'Application\Fieldset\MessageFieldset',
            'name' => 'fieldset_message',
            'options' => array(
                'label' => 'Sua mensagem',
            )
        ));
    }
}