<?php
namespace NaiveContainer;

use Closure;

class ContainerFactory extends Factory
{

    public function register($id, Closure $closure)
    {
        $this->factory_stack[$id] = $closure;
    }

    public function set($id, $value)
    {
        $this->factory_stack[$id] = $value;
    }
}
