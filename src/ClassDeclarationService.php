<?php

namespace EdpSuperluminal;

use Zend\Code\Reflection\ClassReflection;

class ClassDeclarationService
{
    /**
     * Retrieve a class's full class declaration
     * i.e. 'class ClassReflection extends ReflectionClass implements ReflectionInterface'
     *
     * @param ClassReflection $reflection
     * @param $useNames
     * @return string
     */
    public function getClassDeclaration(ClassReflection $reflection, $useNames)
    {
        $declaration = '';

        $declaration .= $this->getClassType($reflection);

        $declaration .= $reflection->getShortName();

        $declaration .= $this->getClassExtendsStatement($reflection, $useNames);

        $declaration .= $this->getInterfaceStatement($reflection, $useNames);

        return $declaration;
    }

    /**
     * Determine the class type (abstract, final, interface, class)
     *
     * @param ClassReflection $reflection
     * @return string
     */
    protected function getClassType(ClassReflection $reflection)
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

    /**
     * Retrieve a class's `extends` statement
     *
     * @param ClassReflection $reflection
     * @param $useNames
     * @return string
     */
    protected function getClassExtendsStatement(ClassReflection $reflection, $useNames)
    {
        $extendsStatement = '';
        $parentName = false;

        if (($parent = $reflection->getParentClass()) && $reflection->getNamespaceName()) {
            $parentName = array_key_exists($parent->getName(), $useNames)
                ? ($useNames[$parent->getName()] ? : $parent->getShortName())
                : ((0 === strpos($parent->getName(), $reflection->getNamespaceName()))
                    ? substr($parent->getName(), strlen($reflection->getNamespaceName()) + 1)
                    : '\\' . $parent->getName());
        } else if ($parent && !$reflection->getNamespaceName()) {
            $parentName = '\\' . $parent->getName();
        }

        if ($parentName) {
            $extendsStatement = " extends {$parentName}";
        }

        return $extendsStatement;
    }

    /**
     * Retrieve a class's `implements` statement
     *
     * @param ClassReflection $reflection
     * @param $useNames
     * @return string
     */
    protected function getInterfaceStatement(ClassReflection $reflection, $useNames)
    {
        $interfaceStatement = '';
        $parent = $reflection->getParentClass();

        $interfaces = array_diff($reflection->getInterfaceNames(), $parent ? $parent->getInterfaceNames() : array());

        if (count($interfaces)) {

            foreach ($interfaces as $interface) {
                $iReflection = new ClassReflection($interface);
                $interfaces = array_diff($interfaces, $iReflection->getInterfaceNames());
            }

            $interfaceStatement .= $reflection->isInterface() ? ' extends ' : ' implements ';
            $interfaceStatement .= implode(', ', array_map(function ($interface) use ($useNames, $reflection) {

                $iReflection = new ClassReflection($interface);

                return (array_key_exists($iReflection->getName(), $useNames)
                    ? ($useNames[$iReflection->getName()] ? : $iReflection->getShortName())
                    : ((0 === strpos($iReflection->getName(), $reflection->getNamespaceName()))
                        ? substr($iReflection->getName(), strlen($reflection->getNamespaceName()) + 1)
                        : '\\' . $iReflection->getName()));

            }, $interfaces));
        }

        return $interfaceStatement;
    }
}