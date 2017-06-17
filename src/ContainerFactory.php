<?php

namespace NaiveContainer;

use Closure;

class ContainerFactory
{

    protected $factory_stack = [];

    public function register($id, Closure $closure)
    {
        $this->factory_stack[$id] = $closure;
    }

    public function set($id, $value)
    {
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
