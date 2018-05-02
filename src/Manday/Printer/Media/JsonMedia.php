<?php

namespace MyPos\Printer\Media;

use MyPos\Printer\Media\AbstractMedia;

class JsonMedia extends AbstractMedia
{
    public function output()
    {
        return json_encode($this->values);
    }
}
