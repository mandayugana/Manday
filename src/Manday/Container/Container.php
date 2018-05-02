<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Manday\Container;

use Manday\Container\ContainerInterface;
use Manday\Container\Exception\ContentNotFoundException;
use Manday\Container\Exception\ClassNotFoundException;
use Manday\Container\Exception\ClassNotInstantiableException;
use Manday\Container\Exception\ClassNotBoundException;
use Manday\Container\Exception\UnresolvableParameterException;

/**
 * Description of Container
 *
 * @author Manda Yugana
 */
class Container implements ContainerInterface
{
    protected $parameters = [];
    
    public function __construct(array $parameters = [])
    {
        foreach ($parameters as $id => $value) {
            $this->bind($id, $value);
        }
    }
    
    public function get(string $id)
    {
        if ($this->has($id) === false) {
            // throw exception
            throw new ContentNotFoundException($id);
        }
        
        $value = $this->parameters[$id];
        if ($value instanceof \Closure) {
            return $value($this);
        } else {
            return $value;
        }
    }
    
    public function has(string $id): bool
    {
        return array_key_exists($id, $this->parameters);
    }
    
    public function bind(string $id, $value): ContainerInterface
    {
        $this->parameters[$id] = $value;
        return $this;
    }
    
    /**
     * Provides a simple autowiring. This is used for autowiring classes that
     * don't require argument for their constructor. For complex instantiation,
     * use <code>ContainerInterface::bind()</code> instead, and use closure as
     * second argument.
     * 
     * @param string $className Name of the class to autowire.
     * @return ContainerInterface The container.
     * @throws \Manday\Container\Exception\ClassNotFoundException If class
     * <code>$className</code> doesn't exist.
     */
    public function autowire(string $id, string $className = null): ContainerInterface
    {
        // in its simplest form, this method can be called with one argument:
        // the id, which describes class name
        if ($className === null) {
            $className = $id;
        }
        
        // simple check to ensure class exists
        if (class_exists($className) === false) {
            throw new ClassNotFoundException($className);
        }
        
        // create alias if the id is different from class name
        if ($id !== $className) {
            $this->setAlias($id, $className);
        }
        
        $this->bind($className, function (Container $container) use ($className) {
            return $container->resolve($className);
        });
        
        return $this;
    }
    
    public function setAlias(string $from, string $to): ContainerInterface
    {
        $this->bind($from, function (ContainerInterface $container) use ($to) {
            // forward $from to $to
            return $container->get($to);
        });
        return $this;
    }
    
    protected function resolve(string $className)
    {
        // create reflection class
        $ref = new \ReflectionClass($className);
        if($ref->isInstantiable() === false) {
            throw new ClassNotInstantiableException($className);
        }

        $constructor = $ref->getConstructor();
        if ($constructor === null) {
            // class has no constructor
            return new $className;
        }

        $dependencies = $this->getDependencies($constructor->getParameters());
        return $ref->newInstanceArgs($dependencies);
    }

    protected function getDependencies(array $parameters): array
    {
        $dependencies = [];

        foreach($parameters as $parameter) {
            $class = $parameter->getClass();
            if ($class === null) {
                // non-class parameter
                $dependencies[] = $this->resolveDefaultValue($parameter);
            } elseif ($this->has($class->name)) {
                // class is bound to container
                $dependencies[] = $this->resolve($class->name);
            } else {
                // class is not bound to container
                throw new ClassNotBoundException($class->name);
            }
        }

        return $dependencies;
    }

    protected function resolveDefaultValue(\ReflectionParameter $parameter)
    {
        if($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }
        
        $message = '%s in %s::__construct()';
        $param = '$' . $parameter->getName();
        if ($parameter->hasType()) {
            $param = $parameter->getType() . ' ' . $param;
        }
        throw new UnresolvableParameterException(
            sprintf($message, $param, $parameter->getDeclaringClass()->name)
        );
    }
}
