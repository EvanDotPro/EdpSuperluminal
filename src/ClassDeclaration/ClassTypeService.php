<?php

namespace EdpSuperluminal\ClassDeclaration;

use Zend\Code\Reflection\ClassReflection;

class ClassTypeService
{
    /**
     * Determine the class type (abstract, final, interface, class)
     *
     * @param ClassReflection $reflection
     * @return string
     */
    public function getClassType(ClassReflection $reflection)
    {
        $classType = '';

        if ($reflection->isAbstract() && !$reflection->isInterface()) {
            $classType = 'abstract ';
        }

        if ($reflection->isFinal()) {
            $classType = 'final ';
        }

        if ($reflection->isInterface()) {
            $classType = 'interface ';
        }

        if (!$reflection->isInterface()) {
            $classType = 'class ';
        }

        return $classType;
    }
}