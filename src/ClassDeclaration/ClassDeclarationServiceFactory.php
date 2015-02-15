<?php

namespace EdpSuperluminal\ClassDeclaration;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ClassDeclarationServiceFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ClassUseNameService $classUseNameService */
        $classUseNameService = $serviceLocator->get('EdpSuperluminal\ClassDeclaration\ClassUseNameService');

        return new ClassDeclarationService(
            new ClassTypeService(),
            new ExtendsStatementService($classUseNameService),
            new InterfaceStatementService($classUseNameService)
        );
    }
}