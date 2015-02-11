<?php

namespace EdpSuperluminalTest;

use EdpSuperluminal\CacheCodeGenerator;
use Phake;

class CacheCodeGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /** @var CacheCodeGenerator */
    protected $sut;

    public function setUp()
    {
        $this->sut = new CacheCodeGenerator();
    }

    public function testSomething()
    {
        $mockClassReflection = Phake::mock('Zend\Code\Reflection\ClassReflection');

        $mockFileReflection = Phake::mock('Zend\Code\Reflection\FileReflection');

        Phake::when($mockClassReflection)->getDeclaringFile()->thenReturn($mockFileReflection);

        Phake::when($mockFileReflection)->getUses()->thenReturn(array());

        Phake::when($mockClassReflection)->getInterfaceNames()->thenReturn(array());

        $this->assertNotNull($this->sut->getCacheCode($mockClassReflection));
    }
}