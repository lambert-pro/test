<?php

namespace App\Controllers;

use App\Models\LetMeTestModelCI;

class Index extends BaseController
{
    public function index()
    {
        echo 'welcome to CI '.\CodeIgniter\CodeIgniter::CI_VERSION;
        exit;
    }

    public function test()
    {
        //        $model = new LetMeTestModel();
        /** @var \App\Models\LetMeTestModel */
        $model = model('LetMeTestModel');
        print_r($model->getList());
    }

    public function useValidationClass()
    {
        $request = $this->getJson();
        $request['header'] = intval($this->request->getHeaderLine('header')) ?? 0;

        $rules = [
            "header" => "required|int|len<=>=[2,6]",
            "age" => [
                "required|int|<=>=[1,100]",
                "error_message" => [
                    'required' => 'This is required field.',
                    'int' => 'Please confirm type(int).'
                ]
            ],
            "country" => "checkCountry"
        ];

        $validation = \Config\Services::myValidation();
        if (!$validation->set_rules($rules)->validate($request)) {
            $invalidParameters = $validation->getInvalidParameters();
            $errorMessage = $this->generateErrorMessage($invalidParameters);
            $this->sendValidateionFaild($errorMessage);
        }

        $this->success('Validation pass.');
    }
}