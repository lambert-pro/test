<?php

namespace App\Controllers;

use App\Models\LetMeTestModel;

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


    public function matchSchemeReferenceData()
    {
        include 'BankResponseExample.php';
        return $myArray;


        include(__DIR__."/BankResponseExample.php");
        $request = $this->getJson();
        $bankId = $request['bank_id'] ?? 0;
        $bankResponse = !empty($request['bank_response']) ? $request['bank_response'] : matchResponse($bankId);
print_r($bankResponse);die;
        // SRD is mean SchemeReferenceData
        switch ($bankId){
            case BANK_TEST_BANK:
                $this->handleTsetBank($bankResponse);
                break;
            case BANK_CASHFLOW:

                break;
            case BANK_FINARO:

                break;
            case BANK_BARCLAYCARD:
                $SRD = $this->handleBarclaycardSRD($bankResponse);
                break;
            default :
                $SRD = "NULL";
                break;
        }

        echo "SchemeReferenceData:".$SRD;
    }

    protected function handleTsetBank($bankResponse)
    {

    }

    protected function handleCashflow($bankResponse)
    {

    }

    protected function handleFinaro($bankResponse)
    {

    }

    protected function handleBarclaycardSRD($bankResponse)
    {
        $bankResponseArr = [];
        preg_match("/(\d{1})(\d{8})(\d{4})(\w{2})([\w]{2})(\w{1})(\w{6})?(\d{0,11})([\w\d\s:@#$%\\\*.?\[\]\/+=;~!,”’`\'\"]*)(\w*)(\d*)(\d*)(\d*)(\d{0,6})([\w\w\s]*)()?/i", $bankResponse, $match);

        $bankResponseArr['dial_indicator'] = $match[1] ?? '';
        $bankResponseArr['terminal_identity'] = $match[2] ?? '';
        $bankResponseArr['message_number'] = $match[3] ?? '';
        $bankResponseArr['message_type'] = $match[4] ?? '';
        $bankResponseArr['acquirer_response_code'] = $match[5] ?? '';
        $bankResponseArr['confirmation_request'] = $match[6] ?? '';
        $bankResponseArr['authorization_code'] = $match[7] ?? '';
        $bankResponseArr['transaction_amount'] = $match[8] ?? '';
        $bankResponseArr['text_message'] = $match[9] ?? '';
        $bankResponseArr['referral_telephone_number'] = $match[10] ?? '';
        $bankResponseArr['floor_limit'] = $match[11] ?? '';
        $bankResponseArr['date'] = $match[12] ?? '';
        $bankResponseArr['ic_response_data'] = $match[13] ?? '';
        $bankResponseArr['response_additional_data'] = $match[14] ?? '';
        $bankResponseArr['auxiliary_data'] = $match[15] ?? '';

        $auxiliaryData = $bankResponseArr['auxiliary_data'];
        preg_match('/1001([\w\s]*)/i', $auxiliaryData, $adMatch);
        $SRD = $adMatch[1] ?? '';
        return $SRD;
    }
}