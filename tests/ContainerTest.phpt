<?php

namespace NaiveContainer\Test;

use NaiveContainer\ContainerFactory;
use NaiveContainer\Exceptions\ContainerException;
use NaiveContainer\Exceptions\DuplicateKeyException;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use Tester\Assert;
use Tester\Environment;
use Tester\TestCase;

require dirname(__DIR__) . '/vendor/autoload.php';

Environment::setup();

class ContainerTest extends TestCase
{

    const SET_COMPONENT_ID = 'set_component_id';
    const REGISTER_COMPONENT_ID = 'register_component_id';
    const EXPECTED_VALUE = 42;
    const INVALID_COMPONENT_ID = 'invalid_component_id';

    protected $factory = null;

    public function setUp()
    {
        $factory = new ContainerFactory();
        $factory->set(self::SET_COMPONENT_ID, self::EXPECTED_VALUE);
        $this->factory = $factory;
    }

    public function testNoComponent()
    {
        $container = $this->factory->createContainer();
        Assert::exception(function() use ($container) {
            $container->get(self::INVALID_COMPONENT_ID);
        }, NotFoundExceptionInterface::class);
    }

    public function testSetAndGetComponent()
    {
        Assert::exception(function() {
            $this->factory->set(self::SET_COMPONENT_ID, 100);
        }, DuplicateKeyException::class);

        $container = $this->factory->createContainer();

        Assert::equal(42, $container->get(self::SET_COMPONENT_ID));
    }

    public function testHasComponent()
    {
        $container = $this->factory->createContainer();

        Assert::true($container->has(self::SET_COMPONENT_ID));

        Assert::false($container->has(self::INVALID_COMPONENT_ID));
    }

    public function testRegisterComponent()
    {
        $this->factory->register(self::REGISTER_COMPONENT_ID, function($container) {
            return $container->get(self::SET_COMPONENT_ID);
        });
        $container = $this->factory->createContainer();
        Assert::equal(self::EXPECTED_VALUE, $container->get(self::REGISTER_COMPONENT_ID));
    }

    public function testRegisterException()
    {
        $this->factory->register(self::REGISTER_COMPONENT_ID, function() {
            throw new RuntimeException();
        });
        $container = $this->factory->createContainer();

        Assert::exception(function() use ($container) {
            $container->get(self::REGISTER_COMPONENT_ID);
        }, ContainerException::class);
    }

    public function testProvider()
    {
        $this->factory->addProvider(new TestProvider());
        $container = $this->factory->createContainer();
        
        Assert::equal(TestProvider::EXPECTED_VALUE, $container->get(TestProvider::PROVIDER_VALUE));
        
        Assert::equal(TestProvider::EXPECTED_VALUE, $container->get(TestProvider::PROVIDER_SERVICE));
    }
}

(new ContainerTest)->run();


