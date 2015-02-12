<?php

namespace EdpSuperluminal;

use Zend\Code\Reflection\ClassReflection;

/**
 * todo - this can be broken down into individual specifications and probably should
 */
class ShouldCacheClassSpecification
{
    public function isSatisfiedBy($class, array $knownClasses)
    {
        // Skip non-Zend classes
        if (0 !== strpos($class, 'Zend')) {
            return false;
        }

        // Skip the autoloader factory
        if ($class === 'Zend\Loader\AutoloaderFactory') {
            return false;
        }

        if ($class === 'Zend\Loader\SplAutoloader') {
            return false;
        }

        // Skip any classes we already know about
        if (in_array($class, $knownClasses)) {
            return false;
        }

        $class = new ClassReflection($class);

        // Skip any Annotation classes
        $docBlock = $class->getDocBlock();
        if ($docBlock) {
            if ($docBlock->getTags('Annotation'))
                return false;
        }

        // Skip ZF2-based autoloaders
        if (in_array('Zend\Loader\SplAutoloader', $class->getInterfaceNames())) {
            return false;
        }

        // Skip classes in this module
        if (0 === strpos($class->getNamespaceName(), 'EdpSuperluminal')) {
            return false;
        }

        // Skip internal classes or classes from extensions
        // (this shouldn't happen, as we're only caching Zend classes)
        if ($class->isInternal() || $class->getExtensionName()) {
            return false;
        }

        return true;
    }
}