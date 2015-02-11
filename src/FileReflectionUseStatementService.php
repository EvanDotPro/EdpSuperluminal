<?php

namespace EdpSuperluminal;

use Zend\Code\Reflection\FileReflection;

class FileReflectionUseStatementService
{
    /**
     * @param FileReflection $declaringFile
     * @return UseStatementDto
     */
    public function getUseStatementDto(FileReflection $declaringFile)
    {
        $useString = '';
        $usesNames = array();

        if (count($uses = $declaringFile->getUses())) {
            foreach ($uses as $use) {
                $usesNames[$use['use']] = $use['as'];

                $useString .= "use {$use['use']}";

                if ($use['as']) {
                    $useString .= " as {$use['as']}";
                }

                $useString .= ";\n";
            }
        }

        return new UseStatementDto($useString, $usesNames);
    }
}