<?php

namespace EdpSuperluminal\ClassDeclaration;

use Zend\Code\Reflection\ClassReflection;

class ExtendsStatementService
{
    /**
     * @var FileReflectionUseStatementService
     */
    protected $fileReflectionUseStatementService;

    public function __construct(FileReflectionUseStatementService $fileReflectionUseStatementService)
    {
        $this->fileReflectionUseStatementService = $fileReflectionUseStatementService;
    }

    /**
     * Retrieve a class's `extends` statement
     *
     * @param ClassReflection $reflection
     * @return string
     */
    public function getClassExtendsStatement(ClassReflection $reflection)
    {
        $extendsStatement = '';

        $parentName = $this->getParentName($reflection);

        if ($parentName) {
            $extendsStatement = " extends {$parentName}";
        }

        return $extendsStatement;
    }

    private function getParentName(ClassReflection $classReflection)
    {
        $useNames = $this->fileReflectionUseStatementService->getUseNames($classReflection->getDeclaringFile());
        $parentName = false;

        if (($parent = $classReflection->getParentClass()) && $classReflection->getNamespaceName()) {

            if (array_key_exists($parent->getName(), $useNames)) {
                $parentName = ($useNames[$parent->getName()] ? : $parent->getShortName());
            } else if (((0 === strpos($parent->getName(), $classReflection->getNamespaceName())))) {
                $parentName = substr($parent->getName(), strlen($classReflection->getNamespaceName()) + 1);
            } else {
                $parentName = '\\' . $parent->getName();
            }
        } else if ($parent && !$classReflection->getNamespaceName()) {
            $parentName = '\\' . $parent->getName();
        }

        return $parentName;
    }
}