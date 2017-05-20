# naive-container
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Logifire/naive-container/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Logifire/naive-container/?branch=master) 
[![Build Status](https://scrutinizer-ci.com/g/Logifire/naive-container/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Logifire/naive-container/build-status/master)

Just a try on a [PSR-11](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md) container implementation

## Usage

```
$factory = new ContainerFactory();

$factory->set('my_value', 42);

$factory->register('my_service', function($container) {
    return 100 + $container->get('my_value');
});

$container = $factory->createContainer();

echo $container->get('my_value'); // 42

echo $container->get('my_service'); // 142
```