<?php
namespace NaiveContainer\Test;

use NaiveContainer\ContainerFactory;
use NaiveContainer\Exceptions\ContainerException;
use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

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
        $this->expectException(NotFoundExceptionInterface::class);
        $container = $this->factory->createContainer();
        $container->get(self::INVALID_COMPONENT_ID);
    }

    public function testSetAndGetComponent()
    {
        $container = $this->factory->createContainer();

        $this->assertSame(42, $container->get(self::SET_COMPONENT_ID));
    }

    public function testHasComponent()
    {
        $container = $this->factory->createContainer();

        $this->assertTrue($container->has(self::SET_COMPONENT_ID));

        $this->assertFalse($container->has(self::INVALID_COMPONENT_ID));
    }

    public function testRegisterComponent()
    {
        $this->factory->register(self::REGISTER_COMPONENT_ID, function($container) {
            return $container->get(self::SET_COMPONENT_ID);
        });
        $container = $this->factory->createContainer();
        $this->assertSame(self::EXPECTED_VALUE, $container->get(self::REGISTER_COMPONENT_ID));
    }

    public function testRegisterException()
    {
        $this->expectException(ContainerException::class);

        $this->factory->register(self::REGISTER_COMPONENT_ID, function() {
            throw new RuntimeException();
        });
        $container = $this->factory->createContainer();

        $container->get(self::REGISTER_COMPONENT_ID);
    }

    public function testProvider()
    {
        $this->factory->addProvider(new TestProvider());
        $container = $this->factory->createContainer();

        $this->assertSame(TestProvider::EXPECTED_VALUE, $container->get(TestProvider::PROVIDER_VALUE));

        $this->assertSame(TestProvider::EXPECTED_VALUE, $container->get(TestProvider::PROVIDER_SERVICE));
    }
}
