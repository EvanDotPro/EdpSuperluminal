<?php

namespace EdpSuperluminalTest;

use EdpSuperluminal\UseStatementDto;

class UseStatementDtoTest extends \PHPUnit_Framework_TestCase
{
    public function testDto()
    {
        $useString = 'use All\\The\\Things';

        $useNames = array(array('use' => 'All\\The\\Things'));

        $dto = new UseStatementDto($useString, $useNames);

        $this->assertEquals($useString, $dto->getUseString());

        $this->assertEquals($useNames, $dto->getUseNames());
    }
}