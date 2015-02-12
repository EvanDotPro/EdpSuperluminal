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
        $this->assertEquals('', $this->sut->getUseString($this->declaringFile));
    }

    public function testSingleUseStatement()
    {
        $namespace = 'All\\The\\Things';

        $expectedUseString = "use {$namespace};\n";

        Phake::when($this->declaringFile)->getUses()->thenReturn(array(array('use' => $namespace, 'as' => null)));

        $this->assertEquals($expectedUseString, $this->sut->getUseString($this->declaringFile));
    }

    public function testSingleUseWithAsStatement()
    {
        $namespace = 'All\\The\\Things';

        $as = 'TheThing';

        $expectedUseString = "use {$namespace} as {$as};\n";

        Phake::when($this->declaringFile)->getUses()->thenReturn(array(array('use' => $namespace, 'as' => $as)));

        $this->assertEquals($expectedUseString, $this->sut->getUseString($this->declaringFile));
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

        $this->assertEquals($expectedUseString, $this->sut->getUseString($this->declaringFile));
    }
}