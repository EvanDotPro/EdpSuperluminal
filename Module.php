<?php

namespace EdpSuperluminal;

use Zend\Code\Reflection\ClassReflection,
    Zend\Code\Scanner\FileScanner,
    Zend\EventManager\StaticEventManager;

/**
 * Create a class cache of all classes used.
 *
 * @package EdpSuperluminal
 */
class Module
{
    protected $knownClasses = array();

    /**
     * Attach events
     * 
     * @return void
     */
    public function init()
    {
        $events = StaticEventManager::getInstance();
        $events->attach('Zend\Mvc\Application', 'finish', array($this, 'cache'));
    }

    /**
     * Cache declared interfaces and classes to a single file
     * 
     * @param  \Zend\Mvc\MvcEvent $e 
     * @return void
     */
    public function cache($e)
    {
        if (!$e->getRequest()->query()->get('buildCache')) {
            return;
        }

        if (file_exists(ZF_CLASS_CACHE)) {
            $this->reflectClassCache();
            $code = file_get_contents(ZF_CLASS_CACHE);
        } else {
            $code = "<?php\n";
        }
    
        $classes = array_merge(get_declared_interfaces(), get_declared_classes());
        foreach ($classes as $class) {
            // Skip the autoloader factory and this class
            if (in_array($class, array('Zend\Loader\AutoloaderFactory', __CLASS__))) {
                continue;
            }

            // Skip any classes we already know about
            if (in_array($class, $this->knownClasses)) {
                continue;
            }

            $class = new ClassReflection($class);

            // Skip internal classes or classes from extensions
            if ($class->isInternal()
                || $class->getExtensionName()
            ) {
                continue;
            }

            // Skip ZF2-based autoloaders
            if (in_array('Zend\Loader\SplAutoloader', $class->getInterfaceNames())) {
                continue;
            }

            $code .= static::getCacheCode($class);
        }

        file_put_contents(ZF_CLASS_CACHE, $code);
    }

    /**
     * Generate code to cache from class reflection.
     *
     * This is a total mess, I know. Just wanted to flesh out the logic.
     * @todo Refactor into a class, clean up logic, DRY it up, maybe move 
     *       some of this into Zend\Code
     * @param  ClassReflection $r
     * @return string
     */
    protected static function getCacheCode(ClassReflection $r)
    {
        $useString = '';
        $usesNames = array();
        if (count($uses = $r->getDeclaringFile()->getUses())) { 
            $useString = "\nuse ";
            $lastUse   = array_pop($uses);

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

        if ($parent = $r->getParentClass()) {
            $parentName   = array_key_exists($parent->getName(), $usesNames) 
                          ? ($usesNames[$parent->getName()] ?: $parent->getShortName()) 
                          : ((0 === strpos($parent->getName(), $r->getNamespaceName())) 
                            ? substr($parent->getName(), strlen($r->getNamespaceName()) + 1) 
                            : '\\' . $parent->getName()); 

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
        $classContents = str_replace('__DIR__', sprintf("'%s'", $classFileDir), $classContents);

        return "\nnamespace " 
               . $r->getNamespaceName()
               . " {\n"
               . $useString
               . $declaration . "\n"
               . strstr($classContents, '{') // messes up when 'implements' is on separate line
               . "\n}\n";
    }

    /**
     * Determine what classes are present in the cache
     * 
     * @return void
     */
    protected function reflectClassCache()
    {
        $scanner = new FileScanner(ZF_CLASS_CACHE);
        $this->knownClasses = $scanner->getClassNames();
    }
}
