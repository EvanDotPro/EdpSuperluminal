<?php

namespace EdpSuperluminal;

use Zend\Code\Reflection\ClassReflection;

class CacheCodeGenerator
{
    /**
     * @var FileReflectionUseStatementService
     */
    protected $fileReflectionService;

    /**
     * @var ClassDeclarationService
     */
    protected $classDeclarationService;

    /**
     * @param FileReflectionUseStatementService $fileReflectionService
     * @param ClassDeclarationService $classDeclarationService
     */
    public function __construct(FileReflectionUseStatementService $fileReflectionService, ClassDeclarationService $classDeclarationService)
    {
        $this->fileReflectionService = $fileReflectionService;
        $this->classDeclarationService = $classDeclarationService;
    }

    /**
     * Generate code to cache from class reflection.
     *
     * @todo maybe move some of this into Zend\Code
     *
     * @param  ClassReflection $classReflection
     * @return string
     */
    public function getCacheCode(ClassReflection $classReflection)
    {
        $useStatementDto = $this->fileReflectionService->getUseStatementDto($classReflection->getDeclaringFile());

        $useString = $useStatementDto->getUseString();
        $useNames = $useStatementDto->getUseNames();

        $declaration = $this->classDeclarationService->getClassDeclaration($classReflection, $useNames);

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
