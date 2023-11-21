<?php
/** 控制反转实例 */

// 创建 注册服务和返回服务实例 的Container类
class Container
{
    private array $services = [];

    /**
     * 服务工厂作为回调函数传递，并将其存储在$services 中，使用服务名作为键名。
     * callable类型用于描述一个函数或方法的签名（类型、返回值等），并指明该函数或方法可以用作参数或返回值。这样可以提高代码的灵活性和重用性。
     */
    public function register(string $serviceName, callable $serviceFactory): void
    {
        $this->services[$serviceName] = $serviceFactory;
    }

    public function get(string $serviceName): object
    {
        // 如果不存在就创建
        if (!isset($this->services[$serviceName])){
            $this->register($serviceName, function () use($serviceName){return new $serviceName();});
        }
        return $this->services[$serviceName]();
    }
}

class Database
{
    public function connect(): string
    {
        return "Connect to database.";
    }
}

class Logger
{
    public function log(string $mag): string
    {
        return "Logged msg:". $mag;
    }
}


$container = new Container();
//$container->register('Database', function (){return new Database();});
//$container->register('Logger', function (){return new Logger();});

$database = $container->get("Database");
$logger = $container->get("Logger");

echo $database->connect() . PHP_EOL;
echo $logger->log("Hello world.") . PHP_EOL;


/**
 * 依赖注入（Dependency Injection，DI）和控制反转（Inversion of Control，IoC）虽然常常被一起提及，但它们是两个不同的概念，其主要区别如下：

1. 意图不同：DI 是一种通过将依赖对象作为参数传递给构造函数、方法或属性的方式，将依赖关系从高层次的组件中解耦开来的设计模式。而 IoC 是一种将控制权交给框架或容器的设计模式，由它们来实例化对象、管理依赖并控制应用程序的整体生命周期。
2. 应用范围不同：DI 是一种编码实践，可以应用到所有编程语言和框架中；而 IoC 主要是一种设计模式，一般应用于面向对象编程中的框架或容器中，实现了系统中各个组件之间的解耦和松散耦合。
3. 实现方式不同：DI 通常通过构造函数注入、属性注入或方法注入来实现依赖关系的管理；而 IoC 一般在某种程度上依赖于 DI，它通过工厂模式、注解（annotation）、XML 配置文件或属性文件等方式来管理和组装对象之间的依赖关系。
4. 实现复杂度不同：DI 通常较为简单，需要手动创建对象并将所需的依赖项传递给它们即可；而 IoC 实际上是一种更高层次的抽象，它需要更复杂的框架和容器来管理对象的构建、依赖项解析、组装和生命周期等方面的处理。

总之，依赖注入和控制反转都是用于实现组件之间的解耦的设计模式，它们各自强调不同的实现方式和应用范围，可以针对不同的问题场景进行选择。
 */
