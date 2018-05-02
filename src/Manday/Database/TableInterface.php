<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Manday\Database;

/**
 *
 * @author Manda Yugana
 */
interface TableInterface
{
    public function read();
    
    public function save();
    
    public function delete();
}
