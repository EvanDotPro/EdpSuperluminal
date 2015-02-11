<?php

namespace EdpSuperluminalTest;

use EdpSuperluminal\FileReflectionUseStatementService;
use Phake;
use Zend\Code\Reflection\FileReflection;

class FileReflectionUseStatementServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var FileReflectionUseStatementService */
    protected $sut;

    /** @var FileReflection */
    protected $declaringFile;

    public function setUp()
    {
        $this->sut = new FileReflectionUseStatementService();

        $this->declaringFile = Phake::mock('Zend\Code\Reflection\FileReflection');
    }

    public function testReturnsEmptyUseStringIfNoUseStatements()
    {
        $dto = $this->sut->getUseStatementDto($this->declaringFile);

        $this->assertEquals('', $dto->getUseString());
    }

    public function testSingleUseStatement()
    {
        $namespace = 'All\\The\\Things';

        $expectedUseString = "use {$namespace};\n";

        Phake::when($this->declaringFile)->getUses()->thenReturn(array(array('use' => $namespace, 'as' => null)));

        $dto = $this->sut->getUseStatementDto($this->declaringFile);

        $this->assertEquals($expectedUseString, $dto->getUseString());
    }

    public function testSingleUseWithAsStatement()
    {
        $namespace = 'All\\The\\Things';

        $as = 'TheThing';

        $expectedUseString = "use {$namespace} as {$as};\n";

        Phake::when($this->declaringFile)->getUses()->thenReturn(array(array('use' => $namespace, 'as' => $as)));

        $dto = $this->sut->getUseStatementDto($this->declaringFile);

        $this->assertEquals($expectedUseString, $dto->getUseString());
    }

    public function testMultipleUseStatements()
    {
        $namespace1 = 'All\\The\\Things';
        $namespace2 = 'Two\\Namespaces';
        $namespace3 = 'One';

        $as1 = null;
        $as2 = '';
        $as3 = 'Two';

        $expectedUseString = "use {$namespace1};\nuse {$namespace2};\nuse {$namespace3} as {$as3};\n";

        Phake::when($this->declaringFile)->getUses()->thenReturn(array(
            array('use' => $namespace1, 'as' => $as1),
            array('use' => $namespace2, 'as' => $as2),
            array('use' => $namespace3, 'as' => $as3)
        ));

        $dto = $this->sut->getUseStatementDto($this->declaringFile);

        $this->assertEquals($expectedUseString, $dto->getUseString());
    }
}