<?php

namespace EdpSuperluminal\ShouldCacheClass;

use Zend\Code\Reflection\ClassReflection;

class IsZendAutoloader implements SpecificationInterface
{

    /**
     * @param ClassReflection $class
     * @return bool
     */
    public function isSatisfiedBy(ClassReflection $class)
    {
        $className = $class->getName();

        return $className === 'Zend\Loader\AutoloaderFactory'
            || $className === 'Zend\Loader\SplAutoloader';
    }
}