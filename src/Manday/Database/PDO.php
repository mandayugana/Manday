<?php

namespace Manday\Database;

class PDO extends \PDO
{
    protected $transactionDepth = 0;
    
    /**
     * {@inheritdoc}
     */
    public function beginTransaction(): bool
    {
        $this->transactionDepth++;
        
        if ($this->transactionDepth === 0) {
            return parent::beginTransaction();
        }
        
        return $this->transactionDepth > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function commit(): bool
    {
        $this->transactionDepth--;
        
        if ($this->transactionDepth === 0) {
            return parent::commit();
        } elseif ($this->transactionDepth > 0) {
            return true;
        } elseif ($this->transactionDepth < 0) {
            $this->transactionDepth = 0;
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rollback(): bool
    {
        $this->transactionDepth--;
        
        if ($this->transactionDepth === 0) {
            return parent::rollBack();
        } elseif ($this->transactionDepth > 0) {
            return true;
        } elseif ($this->transactionDepth < 0) {
            $this->transactionDepth = 0;
            return false;
        }
    }
}
