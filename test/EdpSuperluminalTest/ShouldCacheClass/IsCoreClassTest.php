<?php

namespace EdpSuperluminalTest\ShouldCacheClass;

use EdpSuperluminal\ShouldCacheClass\IsCoreClass;
use Phake;

class IsCoreClassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IsCoreClass
     */
    protected $sut;

    public function setUp()
    {
        $this->sut = new IsCoreClass();
    }

    public function testCoreClassRequirements()
    {
        $classReflection = Phake::mock('Zend\Code\Reflection\ClassReflection');

        $this->assertFalse($this->sut->isSatisfiedBy($classReflection));

        Phake::when($classReflection)->isInternal()->thenReturn(true);

        $this->assertTrue($this->sut->isSatisfiedBy($classReflection));

        Phake::when($classReflection)->isInternal()->thenReturn(false);
        Phake::when($classReflection)->getExtensionName()->thenReturn('Extension');

        $this->assertTrue($this->sut->isSatisfiedBy($classReflection));
    }
}