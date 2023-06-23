<?php

//  检测文本中不能存在3-4和12-19位的连续数字 1234-1234-1234-1234 or 1234123412341234
//  不能有'card', 'number', 'cardnumber', 'cvv', 'cvv2', 'cv2', 'cardno', 'cardnum', 'card-number', 'card-no', 'cnum', 'cardn'等字眼

$customData = "cad4427808001112223337-";

//判断敏感词
$sensitiveWords = ['card', 'number', 'cardnumber', 'cvv', 'cvv2', 'cv2', 'cardno', 'cardnum', 'card-number', 'card-no', 'cnum', 'cardn'];
foreach ($sensitiveWords as $word) {
    if (preg_match("/[\s\S]*{$word}[\s\S]*/i", $customData)){
        $sensitiveWord = $word;
        echo $word;die;
    }
}

// 判断卡号 cvv
preg_match("/\d{12,19}|[\d\-]{19}|[\D]\d{3,4}[\D]/i", $customData, $matches);
if (!empty($matches)){
    print_r($matches[0]);
}



// 判断卡号 cvv
//$customDataArray = str_split($customData);
//$str = '';
//$numberCount = 0;
//$_key = '';
//foreach ($customDataArray as $key => $val) {
//    if (is_numeric($val)) {
//        $str .= $val;
//        $_key .= $key;
//        $numberCount++;
//    } else {
//        if ($numberCount >= 3 && $numberCount <= 4) {
//            echo  'cvv:'.$str;break;
//        } elseif ($numberCount >= 12 && $numberCount <= 19) {
//            echo  'cardNumber:'.$str;break;
//        } else {
//            $str = '';
//            $numberCount = 0;
//        }
//    }
//}

