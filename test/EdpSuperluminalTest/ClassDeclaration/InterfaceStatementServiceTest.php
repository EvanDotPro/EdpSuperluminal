<?php

namespace EdpSuperluminalTest\ClassDeclaration;

use EdpSuperluminal\ClassDeclaration\ClassUseNameService;
use EdpSuperluminal\ClassDeclaration\ExtendsStatementService;
use EdpSuperluminal\ClassDeclaration\InterfaceStatementService;
use EdpSuperluminalTest\AbstractSuperluminalTest;
use Phake;
use Zend\Code\Reflection\ClassReflection;
use Zend\Code\Reflection\FileReflection;

class InterfaceStatementServiceTest extends AbstractSuperluminalTest
{
    /** @var InterfaceStatementService */
    protected $sut;

    /** @var ClassUseNameService */
    protected $classUseNameService;

    public function setUp()
    {
        parent::setUp();

        $this->classUseNameService = Phake::mock('EdpSuperluminal\ClassDeclaration\ClassUseNameService');

        $this->sut = new InterfaceStatementService($this->classUseNameService);

        Phake::when($this->mockClassReflection)->getNamespaceName()->thenReturn('Zend');
    }

    public function testNoInterfaces()
    {
        $this->assertEquals('', $this->sut->getInterfaceStatement($this->mockClassReflection));
    }
}