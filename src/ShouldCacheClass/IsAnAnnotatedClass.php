<?php

namespace EdpSuperluminal\ShouldCacheClass;

use Zend\Code\Reflection\ClassReflection;

class IsAnAnnotatedClass implements SpecificationInterface
{

    /**
     * @param ClassReflection $class
     * @return bool
     */
    public function isSatisfiedBy(ClassReflection $class)
    {
        $docBlock = $class->getDocBlock();
        if ($docBlock) {

            if ($docBlock->getTags('Annotation')) {
                return true;
            }
        }

        return false;
    }
}