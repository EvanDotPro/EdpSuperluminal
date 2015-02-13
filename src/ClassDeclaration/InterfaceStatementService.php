<?php

namespace EdpSuperluminal\ClassDeclaration;

use Zend\Code\Reflection\ClassReflection;

class InterfaceStatementService
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
     * Retrieve a class's `implements` statement
     *
     * @param ClassReflection $reflection
     * @return string
     */
    public function getInterfaceStatement(ClassReflection $reflection)
    {
        $useNames = $this->fileReflectionUseStatementService->getUseNames($reflection->getDeclaringFile());

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