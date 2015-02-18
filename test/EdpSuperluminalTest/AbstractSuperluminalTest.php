<?php

namespace EdpSuperluminalTest;

use EdpSuperluminal\ClassDeclaration\ClassDeclarationService;
use EdpSuperluminal\ClassDeclaration\FileReflectionUseStatementService;
use Phake;
use Zend\Code\Reflection\ClassReflection;
use Zend\Code\Reflection\FileReflection;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractSuperluminalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var FileReflectionUseStatementService
     */
    protected $fileReflectionService;

    /**
     * @var ClassDeclarationService
     */
    protected $classDeclarationService;

    /**
     * @var ClassReflection
     */
    protected $mockClassReflection;

    /**
     * @var FileReflection
     */
    protected $mockFileReflection;
    /**
     * @var \EdpSuperluminal\ClassDeclaration\FileReflectionUseStatementService
     */
    protected $useStatementService;

    public function setUp()
    {
        $this->serviceLocator = Phake::mock('Zend\ServiceManager\ServiceLocatorInterface');

        $this->fileReflectionService = Phake::mock('EdpSuperluminal\ClassDeclaration\FileReflectionUseStatementService');

        $this->classDeclarationService = Phake::mock('EdpSuperluminal\ClassDeclaration\ClassDeclarationService');

        $this->mockClassReflection = Phake::mock('Zend\Code\Reflection\ClassReflection');

        $this->mockFileReflection = Phake::mock('Zend\Code\Reflection\FileReflection');

        Phake::when($this->mockClassReflection)->getDeclaringFile()->thenReturn($this->mockFileReflection);

        Phake::when($this->mockFileReflection)->getUses()->thenReturn(array());

        Phake::when($this->mockClassReflection)->getInterfaceNames()->thenReturn(array());

        Phake::when($this->serviceLocator)->get('EdpSuperluminal\ClassDeclarationService')->thenReturn($this->classDeclarationService);

        Phake::when($this->serviceLocator)->get('EdpSuperluminal\ClassDeclaration\UseStatementService')->thenReturn(new FileReflectionUseStatementService());
        $this->useStatementService = Phake::mock('EdpSuperluminal\ClassDeclaration\FileReflectionUseStatementService');
        Phake::when($this->useStatementService)->getUseNames(Phake::anyParameters())->thenReturn(array());

        Phake::when($this->serviceLocator)->get('EdpSuperluminal\ClassDeclaration\UseStatementService')->thenReturn($this->useStatementService);

    }
}
