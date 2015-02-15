<?php

namespace EdpSuperluminalTest\ClassDeclaration;

use EdpSuperluminal\ClassDeclaration\ClassUseNameService;
use EdpSuperluminal\ClassDeclaration\ExtendsStatementService;
use EdpSuperluminal\ClassDeclaration\InterfaceStatementService;
use Phake;
use Zend\Code\Reflection\ClassReflection;
use Zend\Code\Reflection\FileReflection;

class InterfaceStatementServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var InterfaceStatementService */
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

        $this->sut = new InterfaceStatementService($this->classUseNameService);

        $this->mockClassReflection = Phake::mock('Zend\Code\Reflection\ClassReflection');

        Phake::when($this->mockClassReflection)->getInterfaceNames()->thenReturn(array());

        $this->mockFileReflection = Phake::mock('Zend\Code\Reflection\FileReflection');

        Phake::when($this->mockClassReflection)->getDeclaringFile()->thenReturn($this->mockFileReflection);

        Phake::when($this->mockClassReflection)->getNamespaceName()->thenReturn('Zend');
    }

    public function testNoInterfaces()
    {
        $this->assertEquals('', $this->sut->getInterfaceStatement($this->mockClassReflection));
    }
}