<?php
namespace NaiveContainer;

use Closure;

class ContainerFactory extends ContainerOptions
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
        $container->container_stack = $this->factory_stack;

        return $container;
    }
}
