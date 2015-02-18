<?php

namespace EdpSuperluminalTest;

use EdpSuperluminal\ClassDeclaration\FileReflectionUseStatementService;
use Phake;
use Zend\Code\Reflection\FileReflection;

class FileReflectionUseStatementServiceTest extends AbstractSuperluminalTest
{
    /** @var FileReflectionUseStatementService */
    protected $sut;

    public function setUp()
    {
        parent::setUp();

        $this->sut = new FileReflectionUseStatementService();
    }

    public function testReturnsEmptyUseStringIfNoUseStatements()
    {
        $this->assertEquals('', $this->sut->getUseString($this->mockFileReflection));
    }

    public function testSingleUseStatement()
    {
        $namespace = 'All\\The\\Things';

        $expectedUseString = "use {$namespace};\n";

        Phake::when($this->mockFileReflection)->getUses()->thenReturn(array(array('use' => $namespace, 'as' => null)));

        $this->assertEquals($expectedUseString, $this->sut->getUseString($this->mockFileReflection));
    }

    public function testSingleUseWithAsStatement()
    {
        $namespace = 'All\\The\\Things';

        $as = 'TheThing';

        $expectedUseString = "use {$namespace} as {$as};\n";

        Phake::when($this->mockFileReflection)->getUses()->thenReturn(array(array('use' => $namespace, 'as' => $as)));

        $this->assertEquals($expectedUseString, $this->sut->getUseString($this->mockFileReflection));
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

        Phake::when($this->mockFileReflection)->getUses()->thenReturn(array(
            array('use' => $namespace1, 'as' => $as1),
            array('use' => $namespace2, 'as' => $as2),
            array('use' => $namespace3, 'as' => $as3)
        ));

        $this->assertEquals($expectedUseString, $this->sut->getUseString($this->mockFileReflection));
    }

    public function testReturnsEmptyArrayIfTheresNoUseStatements()
    {
        $this->assertEquals(array(), $this->sut->getUseNames($this->mockFileReflection));
    }


    public function testReturnsFormattedArrayOfUseStatements()
    {
        $uses = array(
            array('use' => 'Zend\Console\Request', 'as' => 'ConsoleRequest'),
            array('use' => 'Zend\Mvc\MvcEvent', 'as' => null)
        );

        $expected = array(
            'Zend\Console\Request' => 'ConsoleRequest',
            'Zend\Mvc\MvcEvent' => null
        );

        Phake::when($this->mockFileReflection)->getUses()->thenReturn($uses);

        $this->assertEquals($expected, $this->sut->getUseNames($this->mockFileReflection));
    }


}