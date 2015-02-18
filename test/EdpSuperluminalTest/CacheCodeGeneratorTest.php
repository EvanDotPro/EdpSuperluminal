<?php

namespace EdpSuperluminalTest;

use EdpSuperluminal\CacheCodeGenerator;
use EdpSuperluminal\CacheCodeGeneratorFactory;
use EdpSuperluminal\ClassDeclaration\ClassDeclarationService;
use EdpSuperluminal\ClassDeclaration\FileReflectionUseStatementService;
use Phake;
use Zend\Code\Reflection\ClassReflection;
use Zend\Code\Reflection\FileReflection;

class CacheCodeGeneratorTest extends AbstractSuperluminalTest
{
    /** @var CacheCodeGenerator */
    protected $sut;

    public function setUp()
    {
        parent::setUp();

        $this->sut = new CacheCodeGenerator($this->fileReflectionService, $this->classDeclarationService);
    }

    public function testThatItPutsTheCacheCodeTogether()
    {
        $namespace = 'Philadelphia';

        Phake::when($this->mockClassReflection)->getNamespaceName()->thenReturn($namespace);

        $cacheCode = $this->sut->getCacheCode($this->mockClassReflection);

        $expectedCacheCode = $this->getExpectedCacheCode($namespace, '', '', '');

        $this->assertEquals($expectedCacheCode, $cacheCode);
    }

    public function testFactory()
    {
        $cacheCodeGeneratorFactory = new CacheCodeGeneratorFactory();

        $this->assertTrue($cacheCodeGeneratorFactory->createService($this->serviceLocator) instanceof CacheCodeGenerator);
    }


    protected function getExpectedCacheCode($namespace, $useString, $declaration, $classContents)
    {
        return "\nnamespace "
        . $namespace
        . " {\n"
        . $useString
        . $declaration . "\n"
        . $classContents
        . "\n}\n";
    }
}