<?php

namespace EdpSuperluminal;

use EdpSuperluminal\ShouldCacheClass\ShouldCacheClassSpecification;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CacheBuilderFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CacheBuilder
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var CacheCodeGenerator $cacheCodeGenerator */
        $cacheCodeGenerator = $serviceLocator->get('EdpSuperluminal\CacheCodeGenerator');

        /** @var ShouldCacheClassSpecification $shouldCacheClass */
        $shouldCacheClass = $serviceLocator->get('EdpSuperluminal\ShouldCacheClass');

        return new CacheBuilder($cacheCodeGenerator, $shouldCacheClass);
    }
}