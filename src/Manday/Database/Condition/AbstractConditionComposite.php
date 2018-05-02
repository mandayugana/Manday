<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Manday\Database\Condition;

use Manday\Database\Condition\ConditionInterface;

/**
 * Description of AbstractConditionComposite
 *
 * @author Manda Yugana
 */
abstract class AbstractConditionComposite implements ConditionInterface
{
    protected $glue = 'AND';
    
    protected $conditions = [];
    
    public function add(ConditionInterface $condition): ConditionInterface
    {
        $this->conditions[] = $condition;
        return $this;
    }
    
    public function __toString(): string
    {
        return '(' . implode($this->glue, $this->conditions) . ')';
    }
}
