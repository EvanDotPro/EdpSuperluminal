<?php

namespace EdpSuperluminal;

use Zend\Code\Reflection\ClassReflection;

class ShouldCacheClassSpecification
{
    public function isSatisfiedBy($class, $currentClass, array $knownClasses)
    {
        // Skip non-Zend classes
        if (0 !== strpos($class, 'Zend')) {
            return false;
        }

        // Skip the autoloader factory and this class
        if (in_array($class, array('Zend\Loader\AutoloaderFactory', $currentClass))) {
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

        // Skip internal classes or classes from extensions
        // (this shouldn't happen, as we're only caching Zend classes)
        if ($class->isInternal() || $class->getExtensionName()) {
            return false;
        }

        return true;
    }
}