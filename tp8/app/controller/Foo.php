<?php

namespace app\controller;

class Foo{
    public function __construct( Bar $bar)
    {
        echo $bar->echo();
    }
}