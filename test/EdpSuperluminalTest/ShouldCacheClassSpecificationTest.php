<?php

namespace EdpSuperluminalTest;

use EdpSuperluminal\ShouldCacheClass\ShouldCacheClassSpecification;
use EdpSuperluminal\ShouldCacheClass\ShouldCacheClassSpecificationFactory;
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
        $shouldCacheClassFactory = new ShouldCacheClassSpecificationFactory();

        $serviceLocator = Phake::mock('Zend\ServiceManager\ServiceLocatorInterface');

        $this->sut = $shouldCacheClassFactory->createService($serviceLocator);

        $this->mockClassReflection = Phake::mock('Zend\Code\Reflection\ClassReflection');
    }

    public function testShouldCacheIfNoSpecificationsFail()
    {
        Phake::when($this->mockClassReflection)->getName()->thenReturn('Zend\Some\Class');

        Phake::when($this->mockClassReflection)->getInterfaceNames()->thenReturn(array());

        $this->assertTrue($this->sut->isSatisfiedBy($this->mockClassReflection));
    }

    /**
     * @covers \EdpSuperluminal\ShouldCacheClass\SpecificationInterface::isSatisfiedBy
     */
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

        $shouldCacheClassFactory = new ShouldCacheClassSpecificationFactory();

        $serviceLocator = Phake::mock('Zend\ServiceManager\ServiceLocatorInterface');

        $this->sut = $shouldCacheClassFactory->createService($serviceLocator, array('FakeClass'));
    }

    public function testFactoryThrowsExceptionIfASpecificationDoesNotExist()
    {
        $this->setExpectedException('\Exception');

        $shouldCacheClassFactory = new ShouldCacheClassSpecificationFactory();

        $serviceLocator = Phake::mock('Zend\ServiceManager\ServiceLocatorInterface');

        $this->sut = $shouldCacheClassFactory->createService($serviceLocator, array('Nonexistant'));
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