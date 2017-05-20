<?php

namespace NaiveContainer;

use NaiveContainer\Exceptions\ContainerException;
use NaiveContainer\Exceptions\NotFoundException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{

    protected $container_stack = [];
    protected $instances = [];

    public function get($id)
    {
        if (!array_key_exists($id, $this->container_stack)) {
            throw new NotFoundException();
        }

        if (is_callable($this->container_stack[$id])) {
            if (!array_key_exists($id, $this->instances)) {
                try {
                    $this->instances[$id] = call_user_func($this->container_stack[$id], $this);
                } catch (NotFoundException $e) {
                    throw $e;
                } catch (\Exception $e) {
                    throw new ContainerException($e->getMessage());
                }
            }
            return $this->instances[$id];
        }

        return $this->container_stack[$id];
    }

    public function has($id)
    {
        return array_key_exists($id, $this->container_stack);
    }
}
