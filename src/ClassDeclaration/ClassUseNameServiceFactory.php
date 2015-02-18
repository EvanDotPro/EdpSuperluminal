<?php

namespace EdpSuperluminal\ClassDeclaration;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ClassUseNameServiceFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var FileReflectionUseStatementService $useStatementService */
        $useStatementService = $serviceLocator->get('EdpSuperluminal\ClassDeclaration\UseStatementService');

        return new ClassUseNameService($useStatementService);
    }
}