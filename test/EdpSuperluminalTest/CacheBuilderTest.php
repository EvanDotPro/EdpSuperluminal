<?php

namespace EdpSuperluminalTest;

use EdpSuperluminal\CacheBuilder;
use EdpSuperluminal\CacheBuilderFactory;
use Phake;

class CacheBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $factory = new CacheBuilderFactory();

        $serviceLocator = Phake::mock('Zend\ServiceManager\ServiceLocatorInterface');

        $cacheCodeGenerator = Phake::mock('EdpSuperluminal\CacheCodeGenerator');

        Phake::when($serviceLocator)->get('EdpSuperluminal\CacheCodeGenerator')->thenReturn($cacheCodeGenerator);

        $shouldCacheClass = Phake::mock('EdpSuperluminal\ShouldCacheClass\ShouldCacheClassSpecification');

        Phake::when($serviceLocator)->get('EdpSuperluminal\ShouldCacheClass')->thenReturn($shouldCacheClass);

        $this->assertTrue($factory->createService($serviceLocator) instanceof CacheBuilder);
    }
}