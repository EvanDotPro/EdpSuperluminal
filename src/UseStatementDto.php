<?php

namespace EdpSuperluminal;

class UseStatementDto
{
    /**
     * @var string
     */
    protected $useString;

    /**
     * @var array
     */
    protected $useNames;

    /**
     * @param string $useString
     * @param array $useNames
     */
    public function __construct($useString = '', $useNames = array())
    {
        $this->useString = $useString;
        $this->useNames = $useNames;
    }

    /**
     * @return string
     */
    public function getUseString()
    {
        return $this->useString;
    }

    /**
     * @return array
     */
    public function getUseNames()
    {
        return $this->useNames;
    }
}