<?php

namespace EdpSuperluminal\ShouldCacheClass;

use Zend\Code\Reflection\ClassReflection;

class ShouldCacheClassSpecification implements SpecificationInterface
{
    /** @var  SpecificationInterface[] */
    protected $specifications;

    /**
     * @param SpecificationInterface[] $specifications
     */
    public function __construct($specifications = array())
    {
        $this->specifications = $specifications;
    }

    public function isSatisfiedBy(ClassReflection $class)
    {
        foreach ($this->specifications as $specification) {
            if ($specification->isSatisfiedBy($class)) {
                return false;
            }
        }

        return true;
    }
}