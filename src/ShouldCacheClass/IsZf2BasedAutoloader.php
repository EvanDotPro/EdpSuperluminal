<?php

namespace EdpSuperluminal\ShouldCacheClass;

use Zend\Code\Reflection\ClassReflection;

class IsZf2BasedAutoloader implements SpecificationInterface
{

    /**
     * @param ClassReflection $class
     * @return bool
     */
    public function isSatisfiedBy(ClassReflection $class)
    {
        return in_array('Zend\Loader\SplAutoloader', $class->getInterfaceNames());
    }
}