<?php

namespace EdpSuperluminalTest\ShouldCacheClass;

use EdpSuperluminal\ShouldCacheClass\IsAnAnnotatedClass;
use Zend\Code\Reflection\ClassReflection;

class IsAnAnnotatedClassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IsAnAnnotatedClass
     */
    protected $sut;

    public function setUp()
    {
        $this->sut = new IsAnAnnotatedClass();
    }

    public function testAnAnnotatedDocBlock()
    {
        $classReflection = new ClassReflection('EdpSuperluminalTest\ShouldCacheClass\AnnotatedClass');
        $this->assertTrue($this->sut->isSatisfiedBy($classReflection));
    }

    public function testAClassWithNoDocBlock()
    {
        $classReflection = new ClassReflection('EdpSuperluminalTest\ShouldCacheClass\NonAnnotatedClass');
        $this->assertFalse($this->sut->isSatisfiedBy($classReflection));
    }

    public function testAClassWithDocBlockButNoTags()
    {
        $classReflection = new ClassReflection('EdpSuperluminalTest\ShouldCacheClass\AlmostAnnotatedClass');
        $this->assertFalse($this->sut->isSatisfiedBy($classReflection));
    }
}

/**
 * @Annotation
 */
class AnnotatedClass
{
    public $a;
}

/**
 *
 */
class AlmostAnnotatedClass
{
    public $a;
}

class NonAnnotatedClass
{

}