<?php

namespace NanoContainer;

use Closure;

class ContainerFactory extends ContainerDecorator implements Factory
{

    /**
     * @var mixed [id => value] May be simple types or closures
     */
    protected $factory_stack = [];

    /**
     * @param string  $id      Example::class
     * @param Closure $closure function(Container $c) {...return $example;}
     * 
     * @return void
     */
    public function register(string $id, Closure $closure): void
    {
        $this->factory_stack[$id] = $closure;
    }

    /**
     * @param string $id    Example::class
     * @param type   $value $example
     * 
     * @return void
     */
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
