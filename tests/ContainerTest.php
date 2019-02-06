<?php
namespace NaiveContainer\Test;

use NaiveContainer\ContainerFactory;
use NaiveContainer\Exceptions\ContainerException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

class ContainerTest extends TestCase
{

    const SET_COMPONENT_ID = 'set_component_id';
    const REGISTER_COMPONENT_ID = 'register_component_id';
    const REGISTER_COMPONENT_NULL_ID = 'register_component_null_id';
    const EXPECTED_VALUE = 42;
    const INVALID_COMPONENT_ID = 'invalid_component_id';

    protected $factory = null;

    /**
     * This method is called before each test.
     */
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
        $this->factory->set(self::REGISTER_COMPONENT_NULL_ID, null);

        $container = $this->factory->createContainer();

        $this->assertSame(self::EXPECTED_VALUE, $container->get(self::SET_COMPONENT_ID));

        $this->assertNull($container->get(self::REGISTER_COMPONENT_NULL_ID));
    }

    public function testHasComponent()
    {
        $this->factory->set(self::REGISTER_COMPONENT_NULL_ID, null);

        $container = $this->factory->createContainer();

        $this->assertTrue($container->has(self::SET_COMPONENT_ID));

        $this->assertFalse($container->has(self::INVALID_COMPONENT_ID));

        $this->assertTrue($container->has(self::REGISTER_COMPONENT_NULL_ID), 'Should return true on key');
    }

    public function testRegisterComponent()
    {
        $this->factory->register(self::REGISTER_COMPONENT_ID, function(ContainerInterface $container) {
            return $container->get(self::SET_COMPONENT_ID);
        });
        $this->factory->register(self::REGISTER_COMPONENT_NULL_ID, function(ContainerInterface $container) {
            return null;
        });

        $container = $this->factory->createContainer();

        $this->assertSame(self::EXPECTED_VALUE, $container->get(self::REGISTER_COMPONENT_ID));
        $this->assertNull($container->get(self::REGISTER_COMPONENT_NULL_ID));
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

    public function testInitializationOptimization()
    {
        $id = 'performance';
        $this->factory->register($id, function() {
            static $var = 0;
            if ($var == 0) {
                $var++;
                return null;
            }
            return $var;
        });
        $container = $this->factory->createContainer();

        $this->assertSame(null, $container->get($id));
        $this->assertSame(null, $container->get($id), 'Should not run the closure initialization twice');
    }
}
