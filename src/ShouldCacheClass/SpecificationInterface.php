<?php

namespace EdpSuperluminal\ShouldCacheClass;

use Zend\Code\Reflection\ClassReflection;

/**
 * This interface is covered by ShouldCacheClassSpecificationTest
 *
 * @codeCoverageIgnore
 */
interface SpecificationInterface
{
    /**
     * @param ClassReflection $class
     * @return bool
     */
    public function isSatisfiedBy(ClassReflection $class);
}