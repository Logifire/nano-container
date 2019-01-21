<?php
namespace NaiveContainer;

use Closure;

class ContainerFactory extends ContainerDecorator implements Factory
{

    /**
     * @var mixed [id => value] May be simple types or closures
     */
    protected $factory_stack = [];

    public function register(string $id, Closure $closure): void
    {
        $this->factory_stack[$id] = $closure;
    }

    public function set(string $id, $value): void
    {
        $this->factory_stack[$id] = $value;
    }

    public function addProvider(FactoryProvider $provider): void
    {
        $provider->register($this);
    }

    public function createContainer(): Container
    {
        $container = new Container();
        $container->container_stack = $this->factory_stack;

        return $container;
    }
}
