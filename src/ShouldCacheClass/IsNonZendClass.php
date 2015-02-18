<?php

namespace EdpSuperluminal\ShouldCacheClass;

use Zend\Code\Reflection\ClassReflection;

class IsNonZendClass implements SpecificationInterface
{
    /**
     * @param ClassReflection $class
     * @return bool
     */
    public function isSatisfiedBy(ClassReflection $class)
    {
        return 0 !== strpos($class->getName(), 'Zend');
    }
}