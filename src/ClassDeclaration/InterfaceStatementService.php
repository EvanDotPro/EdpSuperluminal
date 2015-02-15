<?php

namespace EdpSuperluminal\ClassDeclaration;

use Zend\Code\Reflection\ClassReflection;

class InterfaceStatementService
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
     * Retrieve a class's `implements` statement
     *
     * @param ClassReflection $reflection
     * @return string
     */
    public function getInterfaceStatement(ClassReflection $reflection)
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

            $classUseNameService = $this->classUseNameService;

            $interfaceStatement .= implode(', ', array_map(function ($interface) use ($classUseNameService, $reflection) {

                $interfaceReflection = new ClassReflection($interface);

                return $classUseNameService->getClassUseName($reflection, $interfaceReflection);
            }, $interfaces));
        }

        return $interfaceStatement;
    }
}