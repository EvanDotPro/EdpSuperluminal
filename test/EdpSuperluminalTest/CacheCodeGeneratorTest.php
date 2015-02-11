<?php

namespace EdpSuperluminalTest;

use EdpSuperluminal\CacheCodeGenerator;
use EdpSuperluminal\ClassDeclarationService;
use EdpSuperluminal\FileReflectionUseStatementService;
use EdpSuperluminal\UseStatementDto;
use Phake;

class CacheCodeGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /** @var CacheCodeGenerator */
    protected $sut;

    /**
     * @var FileReflectionUseStatementService
     */
    protected $fileReflectionService;

    /**
     * @var ClassDeclarationService
     */
    protected $classDeclarationService;

    protected $mockClassReflection;

    protected $mockFileReflection;

    protected $mockUseStatementDto;

    public function setUp()
    {
        $this->mockUseStatementDto = Phake::mock('EdpSuperluminal\UseStatementDto');

        $this->fileReflectionService = Phake::mock('EdpSuperluminal\FileReflectionUseStatementService');

        Phake::when($this->fileReflectionService)->getUseStatementDto(Phake::anyParameters())->thenReturn(new UseStatementDto());

        $this->classDeclarationService = Phake::mock('EdpSuperluminal\ClassDeclarationService');

        $this->sut = new CacheCodeGenerator($this->fileReflectionService, $this->classDeclarationService);

        $this->mockClassReflection = Phake::mock('Zend\Code\Reflection\ClassReflection');

        $this->mockFileReflection = Phake::mock('Zend\Code\Reflection\FileReflection');

        Phake::when($this->mockClassReflection)->getDeclaringFile()->thenReturn($this->mockFileReflection);

        Phake::when($this->mockFileReflection)->getUses()->thenReturn(array());

        Phake::when($this->mockClassReflection)->getInterfaceNames()->thenReturn(array());
    }

    public function testThatItPutsTheCacheCodeTogether()
    {
        $namespace = 'Philadelphia';

        Phake::when($this->mockClassReflection)->getNamespaceName()->thenReturn($namespace);

        $cacheCode = $this->sut->getCacheCode($this->mockClassReflection);

        $expectedCacheCode = $this->getExpectedCacheCode($namespace, '', '', '');

        $this->assertEquals($expectedCacheCode, $cacheCode);
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