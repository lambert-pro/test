<?php

namespace app\controller;

use app\BaseController;
use think\Request;

class Index extends BaseController
{
    public function index()
    {

        $foo = invoke('Foo');
        echo 88;die;
    }

    protected $request;
    protected $bar;
    protected $foo;

    public function __construct(Request $request, Bar $bar, Foo $foo)
    {
        $this->request = $request;
        $this->bar = $bar;
    }

    public function hello($name = 'thinkphp8')
    {
        return 'Hello,' . $name . '！This is '. $this->request->action(). ','.$this->bar->echo();
    }

}
