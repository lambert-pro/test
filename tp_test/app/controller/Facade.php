<?php


namespace app\controller;


use app\BaseController;

class Facade extends BaseController
{
    // Facade 门面 文档p50
    public function useFacade()
    {
        // 使用实例化访问方法
//        $test = new Test();
//        echo $test->hello('TP!');

        echo 'Currency ThinkPHP Version: '.\think\facade\App::version().PHP_EOL;
        echo \app\facade\Test::hello('TP!').PHP_EOL;
    }
}