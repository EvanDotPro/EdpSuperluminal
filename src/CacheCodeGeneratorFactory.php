<?php

namespace EdpSuperluminal;

use EdpSuperluminal\ClassDeclaration\ClassDeclarationService;
use EdpSuperluminal\ClassDeclaration\ClassTypeService;
use EdpSuperluminal\ClassDeclaration\ExtendsStatementService;
use EdpSuperluminal\ClassDeclaration\InterfaceStatementService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CacheCodeGeneratorFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CacheCodeGenerator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $fileReflectionUseStatementService = new FileReflectionUseStatementService();

        $classDeclarationService = new ClassDeclarationService(
            new ClassTypeService(),
            new ExtendsStatementService($fileReflectionUseStatementService),
            new InterfaceStatementService($fileReflectionUseStatementService)
        );

        return new CacheCodeGenerator(
            new FileReflectionUseStatementService(),
            $classDeclarationService
        );
    }
}