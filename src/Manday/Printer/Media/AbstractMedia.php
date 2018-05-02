<?php

namespace MyPos\Printer\Media;

use MyPos\Printer\MediaInterface;

abstract class AbstractMedia implements MediaInterface
{
    protected $values;
    
    public function __construct()
    {
        $this->values = new \stdClass();
    }

    /**
     * {@inheritdoc}
     */
    public function with(string $name, string $value): MediaInterface
    {
        $media = new static();
        $media->values = $this->values;
        $media->values->$name = $value;
        return $media;
    }
    
    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return $this->output();
    }
}
