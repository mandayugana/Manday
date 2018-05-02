<?php

namespace Manday\Entity;

interface EntityAwareInterface
{
    /**
     * Tells the entity aware object the entity class name.
     *
     * @param string $className Entity class name.
     * @return void
     */
    public function setEntityClassName(string $className): void;
}
