<?php

namespace EdpSuperluminal\ClassDeclaration;

use Zend\Code\Reflection\ClassReflection;

class ExtendsStatementService
{
    /**
     * @var ClassUseNameService
     */
    protected $classUseNameService;

    public function __construct(ClassUseNameService $classUseNameService)
    {
        $this->classUseNameService = $classUseNameService;
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
        $parentName = false;

        if (($parent = $classReflection->getParentClass()) && $classReflection->getNamespaceName()) {
            $parentName = $this->classUseNameService->getClassUseName($classReflection, $parent);
        } else if ($parent && !$classReflection->getNamespaceName()) {
            $parentName = '\\' . $parent->getName();
        }

        return $parentName;
    }
}