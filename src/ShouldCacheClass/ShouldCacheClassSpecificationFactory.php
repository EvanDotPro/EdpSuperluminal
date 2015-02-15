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

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param array|null $specificationClasses
     * @throws \Exception
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator, $specificationClasses = null)
    {
        if (is_null($specificationClasses)) {
            $specificationClasses = $this->specificationClasses;
        }

        $specifications = array();

        foreach ($specificationClasses as $specificationClass) {
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