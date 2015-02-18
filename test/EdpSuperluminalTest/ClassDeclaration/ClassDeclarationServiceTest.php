<?php

namespace EdpSuperluminalTest\ClassDeclaration;

use EdpSuperluminal\ClassDeclaration\ClassDeclarationService;
use EdpSuperluminal\ClassDeclaration\ClassDeclarationServiceFactory;
use EdpSuperluminal\ClassDeclaration\ClassTypeService;
use EdpSuperluminal\ClassDeclaration\ExtendsStatementService;
use EdpSuperluminal\ClassDeclaration\FileReflectionUseStatementService;
use EdpSuperluminal\ClassDeclaration\InterfaceStatementService;
use EdpSuperluminalTest\AbstractSuperluminalTest;
use Phake;
use Zend\Code\Reflection\ClassReflection;

class ClassDeclarationTest extends AbstractSuperluminalTest
{

    /** @var ClassDeclarationService */
    protected $sut;

    /**
     * @var ClassTypeService
     */
    protected $classTypeService;

    /**
     * @var ExtendsStatementService
     */
    protected $extendsStatementService;

    /**
     * @var InterfaceStatementService
     */
    protected $interfaceStatementService;

    public function setUp()
    {
        parent::setUp();

        $this->classTypeService = Phake::mock('EdpSuperluminal\ClassDeclaration\ClassTypeService');

        $this->extendsStatementService = Phake::mock('EdpSuperluminal\ClassDeclaration\ExtendsStatementService');

        $this->interfaceStatementService = Phake::mock('EdpSuperluminal\ClassDeclaration\InterfaceStatementService');

        $this->sut = new ClassDeclarationService($this->classTypeService, $this->extendsStatementService, $this->interfaceStatementService);;
    }

    public function testBasicClass()
    {
        Phake::when($this->classTypeService)->getClassType(Phake::anyParameters())->thenReturn('class ');

        Phake::when($this->mockClassReflection)->getShortName()->thenReturn('BasicTest');

        $this->assertEquals('class BasicTest', $this->sut->getClassDeclaration($this->mockClassReflection));
    }

    public function testFactory()
    {
        $factory = new ClassDeclarationServiceFactory();

        Phake::when($this->serviceLocator)
            ->get('EdpSuperluminal\ClassDeclaration\ClassUseNameService')
            ->thenReturn(Phake::mock('EdpSuperluminal\ClassDeclaration\ClassUseNameService'));

        $this->assertTrue($factory->createService($this->serviceLocator) instanceof ClassDeclarationService);
    }
}