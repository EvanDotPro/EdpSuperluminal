<?php

namespace EdpSuperluminalTest\ClassDeclaration;

use EdpSuperluminal\ClassDeclaration\ExtendsStatementService;
use EdpSuperluminal\ClassDeclaration\FileReflectionUseStatementService;
use Phake;
use Zend\Code\Reflection\ClassReflection;
use Zend\Code\Reflection\FileReflection;

class ExtendsStatementServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var ExtendsStatementService */
    protected $sut;

    /** @var FileReflectionUseStatementService */
    protected $useStatementService;

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
        $this->useStatementService = Phake::mock('EdpSuperluminal\ClassDeclaration\FileReflectionUseStatementService');

        $this->sut = new ExtendsStatementService($this->useStatementService);

        $this->mockClassReflection = Phake::mock('Zend\Code\Reflection\ClassReflection');

        $this->mockFileReflection = Phake::mock('Zend\Code\Reflection\FileReflection');

        Phake::when($this->mockClassReflection)->getDeclaringFile()->thenReturn($this->mockFileReflection);

        Phake::when($this->mockClassReflection)->getNamespaceName()->thenReturn('Zend');
    }

    public function testDoesNotExtendAnything()
    {
        $this->assertEquals('', $this->sut->getClassExtendsStatement($this->mockClassReflection));
    }

    public function testExtendsAClassWhichIsUsed()
    {
        $parent = Phake::mock('Zend\Code\Reflection\ClassReflection');

        Phake::when($this->mockClassReflection)->getParentClass()->thenReturn($parent);

        $useNames = array('Zend\ServiceManager\ServiceManager' => null);

        Phake::when($this->useStatementService)->getUseNames(Phake::anyParameters())->thenReturn($useNames);

        Phake::when($parent)->getName()->thenReturn('Zend\ServiceManager\ServiceManager');

        Phake::when($parent)->getShortName()->thenReturn('ServiceManager');

        $this->assertEquals(' extends ServiceManager', $this->sut->getClassExtendsStatement($this->mockClassReflection));
    }

    public function testExtendsAClassWhichHasNotBeenUsedAndIsInTheSameNamespace()
    {
        $this->mockParent();

        $this->assertEquals(' extends ServiceManager\ServiceManager', $this->sut->getClassExtendsStatement($this->mockClassReflection));
    }

    public function testExtendsAClassWhichHasNotBeenUsedAndHasADifferentNamespace()
    {
        Phake::when($this->mockClassReflection)->getNamespaceName()->thenReturn('Phake');

        $this->mockParent();

        $this->assertEquals(' extends \Zend\ServiceManager\ServiceManager', $this->sut->getClassExtendsStatement($this->mockClassReflection));
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

        $useNames = array();

        Phake::when($this->useStatementService)->getUseNames(Phake::anyParameters())->thenReturn($useNames);

        Phake::when($parent)->getName()->thenReturn('Zend\ServiceManager\ServiceManager');

        Phake::when($parent)->getShortName()->thenReturn('ServiceManager');
    }
}