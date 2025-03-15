<?php

namespace app\controller;

use app\BaseController;

class NewFeatures extends BaseController
{
    public function index()
    {
        echo "NewFeatures";die;
    }

    public function unionTypes()
    {
        $data = request()->post();
        if (empty($data['foo']) ){
            return 'error';
        }
        var_dump($this->foo($data['foo']));die;
    }

    // PHP 8 引入了联合类型（Union Types），允许一个函数参数或返回值接受多种类型。
    protected function foo(string|int $foo) : string|int {
        $fooType = gettype($foo);
        switch ($fooType){
            case "integer":
                return 1;
            case "string":
            default:
                return '1';
        }
    }
}