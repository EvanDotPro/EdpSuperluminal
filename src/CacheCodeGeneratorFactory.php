<?php

namespace EdpSuperluminal;

use EdpSuperluminal\ClassDeclaration\ClassDeclarationService;
use EdpSuperluminal\ClassDeclaration\ClassTypeService;
use EdpSuperluminal\ClassDeclaration\ExtendsStatementService;
use EdpSuperluminal\ClassDeclaration\FileReflectionUseStatementService;
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
        /** @var FileReflectionUseStatementService $useStatementService */
        $useStatementService = new FileReflectionUseStatementService();

        $classDeclarationService = new ClassDeclarationService(
            new ClassTypeService(),
            new ExtendsStatementService($useStatementService),
            new InterfaceStatementService($useStatementService)
        );

        return new CacheCodeGenerator(
            new FileReflectionUseStatementService(),
            $classDeclarationService
        );
    }
}