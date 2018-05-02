<?php

namespace Manday\Printer;

interface MediaInterface
{
    /**
     * Assigns a parameter to current media.
     * 
     * @param string $name Parameter name.
     * @param string $value Parameter value.
     * @return \Manday\Printer\MediaInterface Media with assigned parameter.
     */
    public function with(string $name, string $value): MediaInterface;
    
    /**
     * Returns the output of this media.
     * 
     * @return string The output of the media.
     */
    public function output(): string;
}
