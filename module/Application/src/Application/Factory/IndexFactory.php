<?php

namespace Application\Factory;

use Application\Controller\IndexController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class IndexFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $services = $serviceLocator->getServiceLocator();
        $entityManager = $services->get('Doctrine\ORM\EntityManager');

        $controller = new IndexController();
        $controller->setEntityManager($entityManager);

        return $controller;
    }
}