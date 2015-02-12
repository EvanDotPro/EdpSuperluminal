<?php

namespace EdpSuperluminal\ClassDeclaration;

use Zend\Code\Reflection\ClassReflection;

class ClassDeclarationService
{
    /**
     * @var ClassTypeService
     */
    protected $classTypeService;

    /**
     * @var ExtendsStatementService
     */
    protected $classExtendsStatementService;

    /**
     * @var InterfaceStatementService
     */
    protected $interfaceStatementService;

    public function __construct(
        ClassTypeService $classTypeService,
        ExtendsStatementService $extendsStatementService,
        InterfaceStatementService $interfaceStatementService
    ) {
        $this->classTypeService = $classTypeService;
        $this->classExtendsStatementService = $extendsStatementService;
        $this->interfaceStatementService = $interfaceStatementService;
    }

    /**
     * Retrieve a class's full class declaration
     * i.e. 'class ClassReflection extends ReflectionClass implements ReflectionInterface'
     *
     * @param ClassReflection $reflection
     * @return string
     */
    public function getClassDeclaration(ClassReflection $reflection)
    {
        $declaration = '';

        $declaration .= $this->classTypeService->getClassType($reflection);

        $declaration .= $reflection->getShortName();

        $declaration .= $this->classExtendsStatementService->getClassExtendsStatement($reflection);

        $declaration .= $this->interfaceStatementService->getInterfaceStatement($reflection);

        return $declaration;
    }
}