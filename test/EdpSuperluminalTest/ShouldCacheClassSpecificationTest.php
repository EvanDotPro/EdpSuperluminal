<?php

namespace EdpSuperluminalTest;

use EdpSuperluminal\ShouldCacheClass\ShouldCacheClassSpecification;
use Phake;
use Zend\Code\Reflection\ClassReflection;

class ShouldCacheClassSpecificationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ShouldCacheClassSpecification
     */
    protected $sut;

    /**
     * @var ClassReflection
     */
    protected $mockClassReflection;

    public function setUp()
    {
        $this->sut = new ShouldCacheClassSpecification();

        $this->mockClassReflection = Phake::mock('Zend\Code\Reflection\ClassReflection');
    }

    public function testShouldCacheIfNoSpecificationsFail()
    {
        $this->assertTrue($this->sut->isSatisfiedBy($this->mockClassReflection));
    }

    public function testShouldNotCacheIfOneSpecificationFails()
    {
        $specifications = array(
            $this->getMockSpecification(),
            $this->getMockSpecification(true),
            $this->getMockSpecification()
        );

        $this->sut = new ShouldCacheClassSpecification($specifications);

        $this->assertFalse($this->sut->isSatisfiedBy($this->mockClassReflection));
    }

    protected function getMockSpecification($isSatisfied = false)
    {
        $specification = Phake::mock('EdpSuperluminal\ShouldCacheClass\SpecificationInterface');

        Phake::when($specification)->isSatisfiedBy($this->mockClassReflection)->thenReturn($isSatisfied);

        return $specification;
    }
}
