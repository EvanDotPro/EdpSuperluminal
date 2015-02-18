<?php

namespace EdpSuperluminalTest;

use EdpSuperluminal\CacheBuilder;
use EdpSuperluminal\CacheBuilderFactory;
use Phake;

class CacheBuilderTest extends AbstractSuperluminalTest
{
    public function testFactory()
    {
        parent::setUp();

        $factory = new CacheBuilderFactory();

        $cacheCodeGenerator = Phake::mock('EdpSuperluminal\CacheCodeGenerator');

        Phake::when($this->serviceLocator)->get('EdpSuperluminal\CacheCodeGenerator')->thenReturn($cacheCodeGenerator);

        $shouldCacheClass = Phake::mock('EdpSuperluminal\ShouldCacheClass\ShouldCacheClassSpecification');

        Phake::when($this->serviceLocator)->get('EdpSuperluminal\ShouldCacheClass')->thenReturn($shouldCacheClass);

        $this->assertTrue($factory->createService($this->serviceLocator) instanceof CacheBuilder);
    }
}