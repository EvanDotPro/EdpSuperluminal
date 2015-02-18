<?php

namespace EdpSuperluminal\ClassDeclaration;

use Zend\Code\Reflection\ClassReflection;

class ClassUseNameService
{
    /**
     * Retrieve a class' ($useClass) name in the context of the current class ($currentClass). If a class has been
     * included in the use statement block of the current class, then we only need the short name (or 'as' name).
     * If not, and the class is in the same namespace, just return the class short name. If the class is from a
     * different namespace, then prefix the FQN with a backslash.
     *
     * @var FileReflectionUseStatementService
     */
    protected $fileReflectionUseStatementService;

    public function __construct(FileReflectionUseStatementService $fileReflectionUseStatementService)
    {
        $this->fileReflectionUseStatementService = $fileReflectionUseStatementService;
    }

    public function getClassUseName(ClassReflection $currentClass, ClassReflection $useClass)
    {
        $useNames = $this->fileReflectionUseStatementService->getUseNames($currentClass->getDeclaringFile());

        $fullUseClassName = $useClass->getName();
        $classUseName = null;

        if (array_key_exists($fullUseClassName, $useNames)) {
            $classUseName = ($useNames[$fullUseClassName] ? : $useClass->getShortName());
        } else if (((0 === strpos($fullUseClassName, $currentClass->getNamespaceName())))) {
            $classUseName = substr($fullUseClassName, strlen($currentClass->getNamespaceName()) + 1);
        } else {
            $classUseName = '\\' . $fullUseClassName;
        }

        return $classUseName;
    }

}