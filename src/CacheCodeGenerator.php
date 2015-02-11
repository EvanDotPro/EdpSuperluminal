<?php

namespace EdpSuperluminal;

use Zend\Code\Reflection\ClassReflection;

class CacheCodeGenerator
{
    /**
     * Generate code to cache from class reflection.
     *
     * @todo clean up logic, DRY it up, maybe move
     *       some of this into Zend\Code
     * @param  ClassReflection $classReflection
     * @return string
     */
    public function getCacheCode(ClassReflection $classReflection)
    {
        $fileReflectionService = new FileReflectionUseStatementService();

        $classDeclarationService = new ClassDeclarationService();

        $useStatementDto = $fileReflectionService->getUseStatementDto($classReflection->getDeclaringFile());

        $useString = $useStatementDto->getUseString();
        $useNames = $useStatementDto->getUseNames();

        $declaration = $classDeclarationService->getClassDeclaration($classReflection, $useNames);

        $classContents = $classReflection->getContents(false);
        $classFileDir  = dirname($classReflection->getFileName());
        $classContents = trim(str_replace('__DIR__', sprintf("'%s'", $classFileDir), $classContents));

        $return = "\nnamespace "
            . $classReflection->getNamespaceName()
            . " {\n"
            . $useString
            . $declaration . "\n"
            . $classContents
            . "\n}\n";

        return $return;
    }
}
