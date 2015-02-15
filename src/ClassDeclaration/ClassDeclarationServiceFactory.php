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
        $useStatementService = new FileReflectionUseStatementService();

        return new ClassDeclarationService(
            new ClassTypeService(),
            new ExtendsStatementService($useStatementService),
            new InterfaceStatementService($useStatementService)
        );
    }
}