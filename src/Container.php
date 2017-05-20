<?php

namespace NaiveContainer;

use NaiveContainer\Exceptions\ContainerException;
use NaiveContainer\Exceptions\NotFoundException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{

    protected $container_stack = [];

    public function get($id)
    {
        if (!array_key_exists($id, $this->container_stack)) {
            throw new NotFoundException();
        }

        if (is_callable($this->container_stack[$id])) {
            try {
                $value = call_user_func($this->container_stack[$id]);
            } catch (\Exception $e) {
                throw new ContainerException($e->getMessage());
            }
            return $value;
        }

        return $this->container_stack[$id];
    }

    public function has($id)
    {
        return array_key_exists($id, $this->container_stack);
    }
}
