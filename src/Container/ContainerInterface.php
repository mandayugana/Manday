<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Manday\Container;

/**
 *
 * @author Manda Yugana
 */
interface ContainerInterface
{
    public function get(string $id);
    
    public function has(string $id): bool;
    
    public function bind(string $id, $value): ContainerInterface;
    
    public function autowire(string $id): ContainerInterface;
    
    public function setAlias(string $from, string $to): ContainerInterface;
}
