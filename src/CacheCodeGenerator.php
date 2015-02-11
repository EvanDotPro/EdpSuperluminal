<?php

namespace EdpSuperluminal;

use Zend\Code\Reflection\ClassReflection;

class CacheCodeGenerator
{
    /**
     * Generate code to cache from class reflection.
     *
     * This is a total mess, I know. Just wanted to flesh out the logic.
     * @todo clean up logic, DRY it up, maybe move
     *       some of this into Zend\Code
     * @param  ClassReflection $r
     * @return string
     */
    public function getCacheCode(ClassReflection $r)
    {
        $useString = '';
        $usesNames = array();
        if (count($uses = $r->getDeclaringFile()->getUses())) {
            foreach ($uses as $use) {
                $usesNames[$use['use']] = $use['as'];

                $useString .= "use {$use['use']}";

                if ($use['as']) {
                    $useString .= " as {$use['as']}";
                }

                $useString .= ";\n";
            }
        }

        $declaration = '';

        if ($r->isAbstract() && !$r->isInterface()) {
            $declaration .= 'abstract ';
        }

        if ($r->isFinal()) {
            $declaration .= 'final ';
        }

        if ($r->isInterface()) {
            $declaration .= 'interface ';
        }

        if (!$r->isInterface()) {
            $declaration .= 'class ';
        }

        $declaration .= $r->getShortName();

        $parentName = false;
        if (($parent = $r->getParentClass()) && $r->getNamespaceName()) {
            $parentName   = array_key_exists($parent->getName(), $usesNames)
                ? ($usesNames[$parent->getName()] ?: $parent->getShortName())
                : ((0 === strpos($parent->getName(), $r->getNamespaceName()))
                    ? substr($parent->getName(), strlen($r->getNamespaceName()) + 1)
                    : '\\' . $parent->getName());
        } else if ($parent && !$r->getNamespaceName()) {
            $parentName = '\\' . $parent->getName();
        }

        if ($parentName) {
            $declaration .= " extends {$parentName}";
        }

        $interfaces = array_diff($r->getInterfaceNames(), $parent ? $parent->getInterfaceNames() : array());
        if (count($interfaces)) {
            foreach ($interfaces as $interface) {
                $iReflection = new ClassReflection($interface);
                $interfaces  = array_diff($interfaces, $iReflection->getInterfaceNames());
            }
            $declaration .= $r->isInterface() ? ' extends ' : ' implements ';
            $declaration .= implode(', ', array_map(function($interface) use ($usesNames, $r) {
                $iReflection = new ClassReflection($interface);
                return (array_key_exists($iReflection->getName(), $usesNames)
                    ? ($usesNames[$iReflection->getName()] ?: $iReflection->getShortName())
                    : ((0 === strpos($iReflection->getName(), $r->getNamespaceName()))
                        ? substr($iReflection->getName(), strlen($r->getNamespaceName()) + 1)
                        : '\\' . $iReflection->getName()));
            }, $interfaces));
        }

        $classContents = $r->getContents(false);
        $classFileDir  = dirname($r->getFileName());
        $classContents = trim(str_replace('__DIR__', sprintf("'%s'", $classFileDir), $classContents));

        $return = "\nnamespace "
            . $r->getNamespaceName()
            . " {\n"
            . $useString
            . $declaration . "\n"
            . $classContents
            . "\n}\n";

        return $return;
    }
}
