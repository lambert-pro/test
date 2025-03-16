<?php

namespace app\controller\index;

use app\BaseController;
use think\facade\App;

class Index extends BaseController
{
    public function index()
    {
        return 'Welcome to the ThinkPHP V'. App::version().'!';
    }

    public function hello($name = 'ThinkPHP8')
    {
        return 'hello,' . $name;
    }

	public function test()
	{
		$a = 2;
		$b = 0;

		$c = $a + $b;

		echo $c;
		echo phpinfo();die;
	}
}
