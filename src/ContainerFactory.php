<?php

namespace NaiveContainer;

use Closure;
use NaiveContainer\Exceptions\DuplicateKeyException;

class ContainerFactory
{

    protected $factory_stack = [];

    public function register($id, Closure $closure)
    {
        if (array_key_exists($id, $this->factory_stack)) {
            throw new DuplicateKeyException();
        }

        $this->factory_stack[$id] = $closure;
    }

    public function set($id, $value)
    {
        if (array_key_exists($id, $this->factory_stack)) {
            throw new DuplicateKeyException();
        }

        $this->factory_stack[$id] = $value;
    }

    public function addProvider(FactoryProvider $provider)
    {
        $provider->register($this);
    }

    public function createContainer()
    {
        $container = new Container();
        $bootstrap = function($stack) {
            $this->container_stack = $stack;
        };
        $bootstrap->call($container, $this->factory_stack);

        return $container;
    }
}
