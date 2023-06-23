<?php
/** 控制反转实例 */

// 定义一个问候服务的接口
interface GreetingService
{
    public function greeting($name);
}

// 实现 GreetingService接口的具体服务
class HelloService implements GreetingService
{
    public function greeting($name)
    {
        echo "Hello ".$name;
    }
}

// 定义一个通过构造函数注入服务的类
class Greeting
{
    private $greetingService;

    public function __construct(GreetingService $greetingService)
    {
        $this->greetingService = $greetingService;
    }

    public function greet($name)
    {
        $this->greetingService->greeting($name);
    }
}

// 定义一个依赖注入容器，用于创建依赖对象并注入
class Container
{
    public $instances = [];

    public function get($class)
    {
        if (!isset($this->instances[$class])) {
            // ReflectionClass 可以通过反射获取一个类的所有信息，并可用于创建和实例化对象、调用类的方法或设置或获取类的属性值。
            $reflector = new ReflectionClass($class);
            $arguments = [];
            foreach ($reflector->getConstructor()->getParameters() as $parameter){
                array_push($arguments, $this->get($parameter->getClass()->getName()));
            }
            $this->instances[$class] = $reflector->newInstanceArgs($arguments);
        }

        return $this->instances[$class];
    }
}


// 在容器中注册服务和依赖
$container = new Container();
$container->instances[HelloService::class] = new HelloService();
$container->instances[GreetingService::class] = $container->get(HelloService::class);
$container->instances[Greeting::class] = $container->get(Greeting::class);

// 通过容器获取依赖服务，然后创建 Greeting 对象
$greeting = $container->get(Greeting::class);
$greeting->greet("Bon");















