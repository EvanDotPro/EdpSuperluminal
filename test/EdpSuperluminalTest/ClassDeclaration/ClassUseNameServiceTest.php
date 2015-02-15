<?php

namespace EdpSuperluminalTest\ClassDeclaration;

use EdpSuperluminal\ClassDeclaration\ClassUseNameService;
use EdpSuperluminal\ClassDeclaration\FileReflectionUseStatementService;
use Phake;
use Zend\Code\Reflection\ClassReflection;
use Zend\Code\Reflection\FileReflection;

class ClassUseNameServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var ClassUseNameService */
    protected $sut;

    /**
     * @var FileReflectionUseStatementService
     */
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

        $this->sut = new ClassUseNameService($this->useStatementService);

        $this->mockClassReflection = Phake::mock('Zend\Code\Reflection\ClassReflection');

        $this->mockFileReflection = Phake::mock('Zend\Code\Reflection\FileReflection');

        Phake::when($this->mockClassReflection)->getDeclaringFile()->thenReturn($this->mockFileReflection);

        Phake::when($this->useStatementService)->getUseNames(Phake::anyParameters())->thenReturn(array());
    }

    public function testAClassWhichHasBeenUsed()
    {
        $useClass = Phake::mock('Zend\Code\Reflection\ClassReflection');

        $useClassFqn = 'Zend\ServiceManager\ServiceManager';

        Phake::when($this->useStatementService)->getUseNames(Phake::anyParameters())->thenReturn(array($useClassFqn => null));

        Phake::when($useClass)->getName()->thenReturn($useClassFqn);
        Phake::when($useClass)->getShortName()->thenReturn('ServiceManager');

        $this->assertEquals('ServiceManager', $this->sut->getClassUseName($this->mockClassReflection, $useClass));
    }

    public function testAClassWhichHasBeenUsedAsSomethingElse()
    {
        $useClass = Phake::mock('Zend\Code\Reflection\ClassReflection');

        $useClassFqn = 'Zend\ServiceManager\ServiceManager';

        Phake::when($this->useStatementService)->getUseNames(Phake::anyParameters())->thenReturn(array($useClassFqn => 'MyServiceManager'));

        Phake::when($useClass)->getName()->thenReturn($useClassFqn);
        Phake::when($useClass)->getShortName()->thenReturn('ServiceManager');

        $this->assertEquals('MyServiceManager', $this->sut->getClassUseName($this->mockClassReflection, $useClass));
    }

    public function testAClassWhichHasNotBeenUsedAndHasTheSameNamespace()
    {
        $useClass = Phake::mock('Zend\Code\Reflection\ClassReflection');

        $useClassFqn = 'Zend\ServiceManager\ServiceManager';

        Phake::when($this->mockClassReflection)->getNamespaceName()->thenReturn('Zend\ServiceManager');
        Phake::when($useClass)->getName()->thenReturn($useClassFqn);
        Phake::when($useClass)->getShortName()->thenReturn('ServiceManager');

        $this->assertEquals('ServiceManager', $this->sut->getClassUseName($this->mockClassReflection, $useClass));
    }

    public function testAClassWhichHasNotBeenUsedAndHasADifferentNamespace()
    {
        $useClass = Phake::mock('Zend\Code\Reflection\ClassReflection');

        $useClassFqn = 'Zend\ServiceManager\ServiceManager';

        Phake::when($this->mockClassReflection)->getNamespaceName()->thenReturn('Zend\Server');
        Phake::when($useClass)->getName()->thenReturn($useClassFqn);
        Phake::when($useClass)->getShortName()->thenReturn('ServiceManager');

        $this->assertEquals('\Zend\ServiceManager\ServiceManager', $this->sut->getClassUseName($this->mockClassReflection, $useClass));
    }
}