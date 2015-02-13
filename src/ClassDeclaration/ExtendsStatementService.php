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
        $useNames = $this->fileReflectionUseStatementService->getUseNames($reflection->getDeclaringFile());

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
}