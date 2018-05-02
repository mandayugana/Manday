<?php

namespace Manday\Printer;

use Manday\Printer\MediaInterface;

interface PrintableInterface
{
    /**
     * Prints current object onto a media.
     * 
     * @param \Manday\Printer\MediaInterface $media The media.
     * @return \Manday\Printer\MediaInterface Printed media.
     */
    public function print(MediaInterface $media): MediaInterface;
}
