<?php

namespace EdpSuperluminalTest\ClassDeclaration;

use EdpSuperluminal\ClassDeclaration\ClassUseNameService;
use EdpSuperluminal\ClassDeclaration\ExtendsStatementService;
use Phake;
use Zend\Code\Reflection\ClassReflection;
use Zend\Code\Reflection\FileReflection;

class ExtendsStatementServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var ExtendsStatementService */
    protected $sut;

    /** @var ClassUseNameService */
    protected $classUseNameService;

    /**
     * @var ClassReflection
     */
    protected $mockClassReflection;

    /**
     * @var FileReflection
     */
    protected $mockFileReflection;

    public function setUp()
    {
        $this->classUseNameService = Phake::mock('EdpSuperluminal\ClassDeclaration\ClassUseNameService');

        $this->sut = new ExtendsStatementService($this->classUseNameService);

        $this->mockClassReflection = Phake::mock('Zend\Code\Reflection\ClassReflection');

        $this->mockFileReflection = Phake::mock('Zend\Code\Reflection\FileReflection');

        Phake::when($this->mockClassReflection)->getDeclaringFile()->thenReturn($this->mockFileReflection);

        Phake::when($this->mockClassReflection)->getNamespaceName()->thenReturn('Zend');
    }

    public function testDoesNotExtendAnything()
    {
        $this->assertEquals('', $this->sut->getClassExtendsStatement($this->mockClassReflection));
    }

    public function testExtendsAClass()
    {
        $parent = Phake::mock('Zend\Code\Reflection\ClassReflection');

        Phake::when($this->mockClassReflection)->getParentClass()->thenReturn($parent);

        Phake::when($this->classUseNameService)->getClassUseName(Phake::anyParameters())->thenReturn('ServiceManager');

        $this->assertEquals(' extends ServiceManager', $this->sut->getClassExtendsStatement($this->mockClassReflection));
    }

    public function testHasNoNamespaceAndExtendsAClassWhichHasNotBeenUsed()
    {
        Phake::when($this->mockClassReflection)->getNamespaceName()->thenReturn(null);

        $this->mockParent();

        $this->assertEquals(' extends \Zend\ServiceManager\ServiceManager', $this->sut->getClassExtendsStatement($this->mockClassReflection));
    }

    protected function mockParent()
    {
        $parent = Phake::mock('Zend\Code\Reflection\ClassReflection');

        Phake::when($this->mockClassReflection)->getParentClass()->thenReturn($parent);

        Phake::when($this->classUseNameService)->getClassUseName()->thenReturn('Zend\ServiceManager\ServiceManager');

        Phake::when($parent)->getName()->thenReturn('Zend\ServiceManager\ServiceManager');

        Phake::when($parent)->getShortName()->thenReturn('ServiceManager');
    }
}