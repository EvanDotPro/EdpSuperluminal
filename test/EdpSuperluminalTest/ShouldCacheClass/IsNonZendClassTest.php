<?php

namespace EdpSuperluminalTest\ShouldCacheClass;

use EdpSuperluminal\ShouldCacheClass\IsNonZendClass;
use Phake;

class IsNonZendClassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IsNonZendClass
     */
    protected $sut;

    public function setUp()
    {
        $this->sut = new IsNonZendClass();
    }

    public function testCoreClassRequirements()
    {
        $classReflection = Phake::mock('Zend\Code\Reflection\ClassReflection');

        Phake::when($classReflection)->getName()->thenReturn('Zend\SomeOtherLib');

        $this->assertFalse($this->sut->isSatisfiedBy($classReflection));

        Phake::when($classReflection)->getName()->thenReturn('EdpSuperluminal\CacheBuilder');

        $this->assertTrue($this->sut->isSatisfiedBy($classReflection));

        Phake::when($classReflection)->getName()->thenReturn('A\Third\Party');

        $this->assertTrue($this->sut->isSatisfiedBy($classReflection));
    }
}