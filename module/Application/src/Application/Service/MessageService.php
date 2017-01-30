<?php
namespace Application\Service;

use Application\Entity\Message;
use Application\Entity\Staff;
use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Common\Collections\Criteria;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class MessageService
{
    /**
     * @var $entity_manager EntityManager
     */
    protected $entity_manager;

    /**
     * @var $hydrator DoctrineHydrator
     */
    protected $hydrator;

    public function __construct(EntityManager $entity_manager)
    {
        $this->entity_manager = $entity_manager;
        $this->hydrator = new DoctrineHydrator($this->entity_manager);
    }

    public function insert($data)
    {
        $pathinfo = pathinfo($data['fieldset_message']['image']['tmp_name']);

        $imgs = array(
            'middle' => array(
                'path' => substr($pathinfo['dirname'], 0, -9) . '/middle/' . $pathinfo['basename'],
                'mode' => \Imagine\Image\ImageInterface::THUMBNAIL_INSET,
                'size' => array(
                    'w' => 735,
                    'h' => 530
                )
            ),
            'thumb' => array(
                'path' => substr($pathinfo['dirname'], 0, -9) . '/thumb/' . $pathinfo['basename'],
                'mode' => \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND,
                'size' => array(
                    'w' => 230,
                    'h' => 165
                )
            )
        );

        $imagine = new Imagine();
        foreach($imgs as $img)
        {
            $size = new Box($img['size']['w'], $img['size']['h']);
            $imagine->open($data['fieldset_message']['image']['tmp_name'])
                ->thumbnail($size, $img['mode'])
                ->save($img['path']);
        }

        $data_staff = array(
            'unit' => $data['fieldset_staff']['id_unit'],
            'name' => $data['fieldset_staff']['name'],
            'email' => $data['fieldset_staff']['email'],
            'departament' => $data['fieldset_staff']['departament'],
        );

        $staff = new Staff();
        $message = new Message();

        $this->hydrator->hydrate($data_staff, $staff);

        $this->entity_manager->persist($staff);
        $this->entity_manager->flush();

        $data_message = array(
            'name' => $data['fieldset_to']['name'],
            'departament' => $data['fieldset_to']['departament'],
            'staff' => $staff->getId(),
            'description' => $data['fieldset_message']['description'],
            'image' => $pathinfo['basename']
        );

        $this->hydrator->hydrate($data_message, $message);

        $this->entity_manager->persist($message);
        $this->entity_manager->flush();

        return $message;
    }

    public function update($data, $id)
    {
        $data_staff = array(
            'unit' => $data['fieldset_staff']['id_unit'],
            'name' => $data['fieldset_staff']['name'],
            'email' => $data['fieldset_staff']['email'],
            'departament' => $data['fieldset_staff']['departament'],
        );

        $data_message = array(
            'description' => $data['fieldset_message']['description'],
        );

        $repository_message = $this->entity_manager->getRepository('Application\Entity\Message');

        $message = $repository_message->findOneById($id);
        $message->getStaff()->setName($data_staff['name']);
        $message->getStaff()->setEmail($data_staff['email']);
        $message->getStaff()->setDepartament($data_staff['departament']);
        $message->getStaff()->setUnit($this->entity_manager->getRepository('Application\Entity\Unit')->findOneById($data_staff['unit']));
        $message->setDescription($data_message['description']);

        $this->entity_manager->persist($message);
        $this->entity_manager->flush();

        return $message;
    }

    public function active($data, $id)
    {
        $repository_message = $this->entity_manager->getRepository('Application\Entity\Message');
        $message = $repository_message->findOneById($id);
        $message->setActive($data['active']);

        $this->entity_manager->persist($message);
        $this->entity_manager->flush();

        return $message;
    }

    public function delete($id)
    {
        $repository_message = $this->entity_manager->getRepository('Application\Entity\Message');
        $message = $repository_message->findOneById($id);

        $this->entity_manager->remove($message);
        $this->entity_manager->flush();

        return true;
    }

    public function findAll()
    {
        $repository_message = $this->entity_manager->getRepository('Application\Entity\Message');
        return $repository_message->findBy(array(), array('id' => 'desc'));
    }

    public function findByActive()
    {
        $repository_message = $this->entity_manager->getRepository('Application\Entity\Message');
        return $repository_message->findByActive(1);
    }

    public function findById($id)
    {
        $repository_message = $this->entity_manager->getRepository('Application\Entity\Message');
        return $repository_message->findOneById($id);
    }

}