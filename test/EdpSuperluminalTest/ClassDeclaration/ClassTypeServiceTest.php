<?php

namespace EdpSuperluminalTest\ClassDeclaration;

use EdpSuperluminal\ClassDeclaration\ClassTypeService;
use EdpSuperluminalTest\AbstractSuperluminalTest;
use Phake;
use Zend\Code\Reflection\ClassReflection;

class ClassTypeServiceTest extends AbstractSuperluminalTest
{
    /** @var ClassTypeService */
    protected $sut;

    public function setUp()
    {
        parent::setUp();

        $this->sut = new ClassTypeService();
    }

    public function testAnAbstractClass()
    {
        Phake::when($this->mockClassReflection)->isAbstract()->thenReturn(true);

        $this->assertEquals('abstract class ', $this->sut->getClassType($this->mockClassReflection));
    }

    public function testANormalClass()
    {
        $this->assertEquals('class ', $this->sut->getClassType($this->mockClassReflection));
    }

    public function testAnInterface()
    {
        Phake::when($this->mockClassReflection)->isInterface()->thenReturn(true);

        $this->assertEquals('interface ', $this->sut->getClassType($this->mockClassReflection));
    }

    public function testAFinalClass()
    {
        Phake::when($this->mockClassReflection)->isFinal()->thenReturn(true);

        $this->assertEquals('final class ', $this->sut->getClassType($this->mockClassReflection));
    }
}