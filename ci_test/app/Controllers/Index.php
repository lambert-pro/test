<?php

namespace App\Controllers;

use App\Models\LetMeTestModel;
use think\Exception;

class Index extends BaseController
{
    public function index()
    {
        echo 'welcome to CI ' . \CodeIgniter\CodeIgniter::CI_VERSION;
        exit;
    }

    public function test()
    {
        //$model = new LetMeTestModel();
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
        global $bankResponseExampleByVisa;
        global $bankResponseExampleByMastercard;
        require_once(__DIR__ . '/BankResponseExample.php');
        $request = $this->getJson();
        $bankId = $request['bank_id'] ?? 0;
        $cardType = strtoupper($request['card_type']) ?? 'MC'; // MC or VISA

        if (!empty($request['bank_response'])) {
            $bankResponse = $request['bank_response'];
        } else {
            if ($cardType == "MC")
                $bankResponse = $bankResponseExampleByMastercard[$bankId] ?? "";
            else
                $bankResponse = $bankResponseExampleByVisa[$bankId] ?? "";
        }

        // SRD is mean SchemeReferenceData
        $SRD = null;
        switch ($bankId) {
            case BANK_TEST_BANK:
                $SRD = $this->handleTsetBank($bankResponse, $cardType);
                break;
            case BANK_CASHFLOW:
                $SRD = $this->handleCashflow($bankResponse, $cardType);
                break;
            case BANK_BARCLAYCARD:
                $SRD = $this->handleBarclaycardSRD($bankResponse, $cardType);
                break;
            case BANK_FINARO:
                $SRD = $this->handleFinaro($bankResponse, $cardType);
                break;
            default :
                break;
        }
        $this->success(['SchemeReferenceData' => $SRD]);
    }

    protected function handleTsetBank($bankResponse, $cardType)
    {
        return 1;
    }

    protected function handleCashflow($bankResponse, $cardType)
    {
        return 1;
    }

    protected function handleFinaro($bankResponse, $cardType)
    {
        return 1;
    }

    protected function handleBarclaycardSRD($bankResponse, $cardType)
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
        return $adMatch[1] ?? '';
    }

    // 冒泡排序
    public function bubbleSort(): string
    {
        $post = $this->getJson();

        if (empty($post) || !isset($post['string'])) {
            $string = '5,7,2,55,1,88';
        } else {
            $string = $post['string'];
        }

        $array = explode(',', $string);
        // 循环次数为 count($arr) + count($arr)-1 + count($arr)-2 + ...... + 2 + 1  => (n*n + n)/2
        $count = 0;
//        for ($i = 0; $i < count($array); $i++) {
//            $count ++;
//            for ($j = $i; $j < count($array) - 1; $j++) {
//                $count ++;
//                // 比较第一个数和第二个数
//                if ($array[$i] > $array[$j+1]) {
//                    $tmp = $array[$i];
//                    $array[$i] = $array[$j+1];
//                    $array[$j+1] = $tmp;
//                }
//            }
//        }

        for ($i = 0; $i < count($array); $i++) {
            $count ++;
            for ($j = $i+1; $j < count($array); $j++) {
                $count ++;
                // 比较第一个数和第二个数
                if ($array[$i] > $array[$j]) {
                    $tmp = $array[$i];
                    $array[$i] = $array[$j];
                    $array[$j] = $tmp;
                }
            }
        }
        return 'sort:'.implode(',', $array).'; count:'.$count;
    }
}