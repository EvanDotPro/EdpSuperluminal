<?php

namespace EdpSuperluminal;

use Zend\Code\Reflection\FileReflection;

class FileReflectionUseStatementService
{
    /**
     * @param FileReflection $declaringFile
     * @return string
     */
    public function getUseString(FileReflection $declaringFile)
    {
        $useString = '';

        if (!count($uses = $declaringFile->getUses())) {
            return $useString;
        }

        foreach ($uses as $use) {
            $useString .= "use {$use['use']}";

            if ($use['as']) {
                $useString .= " as {$use['as']}";
            }

            $useString .= ";\n";
        }

        return $useString;
    }

    /**
     * @param FileReflection $declaringFile
     * @return array
     */
    public function getUseNames(FileReflection $declaringFile)
    {
        $usesNames = array();

        if (!count($uses = $declaringFile->getUses())) {
            return $usesNames;
        }

        foreach ($uses as $use) {
            $usesNames[$use['use']] = $use['as'];
        }

        return $usesNames;
    }
}