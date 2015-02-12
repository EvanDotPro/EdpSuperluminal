<?php

namespace EdpSuperluminal;

use EdpSuperluminal\ClassDeclaration\ClassDeclarationService;
use EdpSuperluminal\ClassDeclaration\ClassTypeService;
use EdpSuperluminal\ClassDeclaration\ExtendsStatementService;
use EdpSuperluminal\ClassDeclaration\InterfaceStatementService;
use Zend\Code\Scanner\FileScanner;
use Zend\Console\Request as ConsoleRequest;

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
    public function init($e)
    {
        $events = $e->getEventManager()->getSharedManager();
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
        $request = $e->getRequest();
        if ($request instanceof ConsoleRequest ||
            $request->getQuery()->get('EDPSUPERLUMINAL_CACHE', null) === null) {
            return;
        }

        if (file_exists(ZF_CLASS_CACHE)) {
            $this->reflectClassCache();
            $code = file_get_contents(ZF_CLASS_CACHE);
        } else {
            $code = "<?php\n";
        }

        $classes = array_merge(get_declared_interfaces(), get_declared_classes());

        $cacheCodeGenerator = $this->buildCacheCodeGenerator();

        $shouldCacheClass = new ShouldCacheClassSpecification();

        foreach ($classes as $class) {
            if (!$shouldCacheClass->isSatisfiedBy($class, $this->knownClasses)) {
                continue;
            }

            $this->knownClasses[] = $class;

            $code .= $cacheCodeGenerator->getCacheCode($class);
        }

        file_put_contents(ZF_CLASS_CACHE, $code);
        // minify the file
        file_put_contents(ZF_CLASS_CACHE, php_strip_whitespace(ZF_CLASS_CACHE));
    }

    /**
     * Determine what classes are present in the cache
     *
     * @return void
     */
    protected function reflectClassCache()
    {
        $scanner = new FileScanner(ZF_CLASS_CACHE);
        $this->knownClasses = array_unique($scanner->getClassNames());
    }

    /**
     * @return CacheCodeGenerator
     */
    protected function buildCacheCodeGenerator()
    {
        $fileReflectionUseStatementService = new FileReflectionUseStatementService();

        $classDeclarationService = new ClassDeclarationService(
            new ClassTypeService(),
            new ExtendsStatementService($fileReflectionUseStatementService),
            new InterfaceStatementService($fileReflectionUseStatementService)
        );

        return new CacheCodeGenerator(
            new FileReflectionUseStatementService(),
            $classDeclarationService
        );
    }
}
