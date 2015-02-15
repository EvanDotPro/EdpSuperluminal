<?php

namespace EdpSuperluminalTest\ClassDeclaration;

use EdpSuperluminal\ClassDeclaration\ClassDeclarationService;
use EdpSuperluminal\ClassDeclaration\ClassDeclarationServiceFactory;
use EdpSuperluminal\ClassDeclaration\ClassTypeService;
use EdpSuperluminal\ClassDeclaration\ExtendsStatementService;
use EdpSuperluminal\ClassDeclaration\InterfaceStatementService;
use Phake;
use Zend\Code\Reflection\ClassReflection;

class ClassDeclarationTest extends \PHPUnit_Framework_TestCase
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

    /**
     * @var ClassReflection
     */
    protected $mockClassReflection;

    public function setUp()
    {
        $this->classTypeService = Phake::mock('EdpSuperluminal\ClassDeclaration\ClassTypeService');

        $this->extendsStatementService = Phake::mock('EdpSuperluminal\ClassDeclaration\ExtendsStatementService');

        $this->interfaceStatementService = Phake::mock('EdpSuperluminal\ClassDeclaration\InterfaceStatementService');

        $this->sut = new ClassDeclarationService($this->classTypeService, $this->extendsStatementService, $this->interfaceStatementService);;

        $this->mockClassReflection = Phake::mock('Zend\Code\Reflection\ClassReflection');
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

        $serviceLocator = Phake::mock('Zend\ServiceManager\ServiceLocatorInterface');

        $this->assertTrue($factory->createService($serviceLocator) instanceof ClassDeclarationService);
    }
}