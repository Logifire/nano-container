<?php
namespace NaiveContainer;

use NaiveContainer\Exceptions\ContainerException;
use NaiveContainer\Exceptions\NotFoundException;
use Psr\Container\ContainerInterface;
use Exception;

class Container extends ContainerDecorator implements ContainerInterface
{

    /**
     * Instantiated parts
     * 
     * @var mixed 
     */
    protected $instances = [];

    public function get($id)
    {
        if (!$this->has($id)) {
            throw new NotFoundException("Couldn't find {$id}");
        }

        if (is_callable($this->container_stack[$id])) {
            // Performance optimization, isset returns false on null values, but is faster
            // https://stackoverflow.com/a/9522522/10604655
            if (!isset($this->instances[$id]) && !array_key_exists($id, $this->instances)) {
                try {
                    $this->instances[$id] = call_user_func($this->container_stack[$id], $this);
                } catch (Exception $e) {
                    throw new ContainerException($e->getMessage());
                }
            }
            return $this->instances[$id];
        }

        return $this->container_stack[$id];
    }

    public function has($id): bool
    {
        return (isset($this->container_stack[$id]) || array_key_exists($id, $this->container_stack));
    }
}
