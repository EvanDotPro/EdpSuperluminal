<?php

namespace EdpSuperluminal;

use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\MvcEvent;

/**
 * Create a class cache of all classes used.
 *
 * @package EdpSuperluminal
 */
class Module
{
    /**
     * Attach the cache event listener
     *
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        /** @var CacheBuilder $cacheBuilder */
        $cacheBuilder = $serviceManager->get('EdpSuperluminal\CacheBuilder');

        $eventManager = $e->getApplication()->getEventManager()->getSharedManager();
        $eventManager->attach('Zend\Mvc\Application', 'finish', function (MvcEvent $e) use ($cacheBuilder) {
            $request = $e->getRequest();

            if ($request instanceof ConsoleRequest ||
                $request->getQuery()->get('EDPSUPERLUMINAL_CACHE', null) === null) {
                return;
            }

            $cacheBuilder->cache(ZF_CLASS_CACHE);
        });
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'EdpSuperluminal\CacheCodeGenerator'     => 'EdpSuperluminal\CacheCodeGeneratorFactory',
                'EdpSuperluminal\CacheBuilder'     => 'EdpSuperluminal\CacheBuilderFactory',
                'EdpSuperluminal\ShouldCacheClass'     => 'EdpSuperluminal\ShouldCacheClass\ShouldCacheClassSpecificationFactory',
                'EdpSuperluminal\ClassDeclarationService'     => 'EdpSuperluminal\ClassDeclaration\ClassDeclarationServiceFactory',
            )
        );
    }
}
