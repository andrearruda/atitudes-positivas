<?php
namespace Application\Factory;

use Application\Controller\MessageController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MessageFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $services = $serviceLocator->getServiceLocator();
        $entityManager = $services->get('Doctrine\ORM\EntityManager');

        $controller = new MessageController();
        $controller->setEntityManager($entityManager);

        return $controller;
    }
}