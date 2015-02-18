<?php

namespace EdpSuperluminalTest;

use EdpSuperluminal\ShouldCacheClass\ShouldCacheClassSpecification;
use EdpSuperluminal\ShouldCacheClass\ShouldCacheClassSpecificationFactory;
use Phake;
use Zend\Code\Reflection\ClassReflection;

class ShouldCacheClassSpecificationTest extends AbstractSuperluminalTest
{
    /**
     * @var ShouldCacheClassSpecification
     */
    protected $sut;


    public function setUp()
    {
        parent::setUp();

        $shouldCacheClassFactory = new ShouldCacheClassSpecificationFactory();

        $this->sut = $shouldCacheClassFactory->createService($this->serviceLocator);
    }

    public function testShouldCacheIfNoSpecificationsFail()
    {
        Phake::when($this->mockClassReflection)->getName()->thenReturn('Zend\Some\Class');

        Phake::when($this->mockClassReflection)->getInterfaceNames()->thenReturn(array());

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

    public function testFactoryThrowsExceptionIfASpecificationDoesNotImplementSpecificationInterface()
    {
        $this->setExpectedException('\Exception');

        $shouldCacheClassFactory = new ShouldCacheClassSpecificationFactory(array('FakeClass'));

        $this->sut = $shouldCacheClassFactory->createService($this->serviceLocator);
    }

    public function testFactoryThrowsExceptionIfASpecificationDoesNotExist()
    {
        $this->setExpectedException('\Exception');

        $shouldCacheClassFactory = new ShouldCacheClassSpecificationFactory(array('Nonexistant'));

        $this->sut = $shouldCacheClassFactory->createService($this->serviceLocator);
    }

    protected function getMockSpecification($isSatisfied = false)
    {
        $specification = Phake::mock('EdpSuperluminal\ShouldCacheClass\SpecificationInterface');

        Phake::when($specification)->isSatisfiedBy($this->mockClassReflection)->thenReturn($isSatisfied);

        return $specification;
    }
}

namespace EdpSuperluminal\ShouldCacheClass;

class FakeClass
{

}