<?php

namespace EdpSuperluminal;

use EdpSuperluminal\ClassDeclaration\ClassDeclarationService;
use EdpSuperluminal\ClassDeclaration\FileReflectionUseStatementService;
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
        $useStatementService = new FileReflectionUseStatementService();

        /** @var ClassDeclarationService $classDeclarationService */
        $classDeclarationService = $serviceLocator->get('EdpSuperluminal\ClassDeclarationService');

        return new CacheCodeGenerator(
            $useStatementService,
            $classDeclarationService
        );
    }
}