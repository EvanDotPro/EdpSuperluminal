<?php

namespace EdpSuperluminal\ShouldCacheClass;

use Zend\Code\Reflection\ClassReflection;

class IsCoreClass implements SpecificationInterface
{

    /**
     * @param ClassReflection $class
     * @return bool
     */
    public function isSatisfiedBy(ClassReflection $class)
    {
        // Skip internal classes or classes from extensions
        // (this shouldn't happen, as we're only caching Zend classes)
        return ($class->isInternal() || $class->getExtensionName());
    }
}