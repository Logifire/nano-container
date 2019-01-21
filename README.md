# naive-container
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Logifire/naive-container/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Logifire/naive-container/?branch=master) 
[![Build Status](https://scrutinizer-ci.com/g/Logifire/naive-container/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Logifire/naive-container/build-status/master)

A [PSR-11](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md) container implementation

## Usage
**Basics**
```
$factory = new ContainerFactory();

$factory->set('my_value', 42);

$factory->register('my_service', function(Container $c) {
    return 100 + $c->get('my_value');
});

$container = $factory->createContainer();

echo $container->get('my_value'); // 42

echo $container->get('my_service'); // 142
```
**Grouping configurations**
You can group configurations into providers, which takes a `ContainerFactory`

```
class ControllerProvider implements FactoryProvider {
    public function register(ContainerFactory $factory) {
        $factory->register(LoginController::class, function(Container $c) {
            return new LoginController();
        });
    }
}

$factory = new ContainerFactory();
$factory->addProvider(new ControllerProvider());
$container = $factory->createContainer();
$controller = $container->get(LoginController::class);
```