<?php

namespace EdpSuperluminal\ShouldCacheClass;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ShouldCacheClassSpecificationFactory implements FactoryInterface
{
    protected $specificationClasses = array(
        'IsNonZendClass',
        'IsZendAutoloader',
        'IsAnAnnotatedClass',
        'IsZf2BasedAutoloader',
        'IsCoreClass'
    );

    public function __construct($specificationClasses = null)
    {
        if (!is_null($specificationClasses)) {
            $this->specificationClasses = $specificationClasses;
        }
    }


    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param array|null $specificationClasses
     * @throws \Exception
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $specifications = array();

        foreach ($this->specificationClasses as $specificationClass) {
            $specificationClass = 'EdpSuperluminal\ShouldCacheClass\\' . $specificationClass;

            if (!class_exists($specificationClass)) {
                throw new \Exception("The specification '{$specificationClass}' does not exist!");
            }

            $specification = new $specificationClass();

            if (!$specification instanceof SpecificationInterface) {
                throw new \Exception("The specifications provided must implement SpecificationInterface!");
            }

            $specifications[] = $specification;
        }

        return new ShouldCacheClassSpecification($specifications);
    }
}