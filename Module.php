<?php

namespace EdpSuperluminal;

use Zend\EventManager\StaticEventManager;

class Module
{
    public function init()
    {
        $events = StaticEventManager::getInstance();
        // This is a total mess, I know. Just wanted to flesh out the logic.
        // @TODO: Refactor into a class, clean up logic, DRY it up, maybe move 
        // some of this into Zend\Code
        $events->attach('Zend\Mvc\Application', 'finish', function($e) {
            if (!$e->getRequest()->query()->get('buildCache') || file_exists(ZF_CLASS_CACHE)) return;
            $getCacheCode = function ($r)
            {
                $useString = '';
                $usesNames = array();
                if (count($uses = $r->getDeclaringFile()->getUses())) { 
                    $useString = "\nuse ";
                    $lastUse = array_pop($uses);
                    foreach ($uses as $use) {
                        $usesNames[$use['use']] = $use['as'];
                        $useString .= "{$use['use']}";
                        if ($use['as']) {
                            $useString .= " as {$use['as']}";
                        }
                        $useString .= ",\n";
                    }
                    $usesNames[$lastUse['use']] = $lastUse['as'];
                    $useString .= "{$lastUse['use']}";
                    if ($lastUse['as']) {
                        $useString .= " as {$lastUse['as']}";
                    }
                    $useString .= ";\n\n";
                }
                $declaration = '';
                if ($r->isAbstract() 
                    && !$r->isInterface())  $declaration .= 'abstract ';
                if ($r->isFinal())          $declaration .= 'final ';
                if ($r->isInterface())      $declaration .= 'interface ';
                if (!$r->isInterface())     $declaration .= 'class ';
                $declaration .= $r->getShortName();
                if ($parent = $r->getParentClass()) {
                    $parentName = array_key_exists($parent->getName(), $usesNames) ? ($usesNames[$parent->getName()] ?: $parent->getShortName()) : ((0 === strpos($parent->getName(), $r->getNamespaceName())) ? substr($parent->getName(), strlen($r->getNamespaceName()) + 1) : '\\' . $parent->getName()); 
                    $declaration .= " extends {$parentName}";
                }
                if (count($interfaces = array_diff($r->getInterfaceNames(), $parent ? $parent->getInterfaceNames() : array()))) {
                    foreach ($interfaces as $interface) {
                        $iReflection = new \Zend\Code\Reflection\ClassReflection($interface);
                        $interfaces = array_diff($interfaces, $iReflection->getInterfaceNames());
                    }
                    $declaration .= $r->isInterface() ? ' extends ' : ' implements ';
                    $lastInterface = array_pop($interfaces);
                    foreach ($interfaces as $interface) {
                        $iReflection = new \Zend\Code\Reflection\ClassReflection($interface);
                        $declaration .= (array_key_exists($iReflection->getName(), $usesNames) ? ($usesNames[$iReflection->getName()] ?: $iReflection->getShortName()) : ((0 === strpos($iReflection->getName(), $r->getNamespaceName())) ? substr($iReflection->getName(), strlen($r->getNamespaceName()) + 1) : '\\' . $iReflection->getName())) . ', ';
                    }
                    $iReflection = new \Zend\Code\Reflection\ClassReflection($lastInterface);
                    $declaration .= array_key_exists($iReflection->getName(), $usesNames) ? ($usesNames[$iReflection->getName()] ?: $iReflection->getShortName()) : ((0 === strpos($iReflection->getName(), $r->getNamespaceName())) ? substr($iReflection->getName(), strlen($r->getNamespaceName()) + 1) : '\\' . $iReflection->getName());
                }
                return "\nnamespace " 
                     . $r->getNamespaceName()
                     . " {\n"
                     . $useString
                     . $declaration . "\n"
                     . strstr($r->getContents(false), '{') // messes up when 'implements' is on separate line
                     . "\n}\n";
            };
     
            $classes = array_merge(get_declared_interfaces(), get_declared_classes());
            $code = "<?php\n";
            foreach ($classes as $class) {
                if (0 !== strpos($class, 'Zend\\')) continue;
                $class = new \Zend\Code\Reflection\ClassReflection($class);
                $code .= $getCacheCode($class);
            }
            file_put_contents(ZF_CLASS_CACHE, $code);
        });
    }
}
