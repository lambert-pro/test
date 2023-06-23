<?php

namespace App\Controllers;

use App\Models\LetMeTestModelCI;

class Index extends BaseController
{
    public function index()
    {
        echo 'welcome to CI '.\CodeIgniter\CodeIgniter::CI_VERSION;
    }

    public function test()
    {
        //        $model = new LetMeTestModel();
        /** @var \App\Models\LetMeTestModel */
        $model = model('LetMeTestModel');
        print_r($model->getList());
    }

}