<?php

namespace EdpSuperluminal\ShouldCacheClass;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ShouldCacheClassSpecificationFactory implements FactoryInterface
{
    protected $specificationClasses = array(
        'IsNonZendClass',
        'IsZendAutoLoader',
        'IsAnAnnotatedClass',
        'IsZf2BasedAutoloader',
        'IsCoreClass'
    );

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @throws \Exception
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $specifications = array();

        foreach ($this->specificationClasses as $specificationClass) {
            $specification = 'EdpSuperluminal\ShouldCacheClass\\' . $specificationClass();

            if (!$specification instanceof SpecificationInterface) {
                throw new \Exception("The specifications provided must implement SpecificationInterface!");
            }

            $specifications[] = $specification;
        }

        return new ShouldCacheClassSpecification($specifications);
    }
}