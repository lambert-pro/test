<?php
//function generateOBHash($data){
//    $hash_tmp = $data["status"] . $data["transaction_id"] . $data["order_id"] . $data["timestamp"];
//    $hash_tmp2 = hash("sha256", $hash_tmp);
//    $hash_tmp2 = $hash_tmp2 . $data["app_key"];
//    $hash = hash("sha256", $hash_tmp2);
//    return $hash;
//}
//
//$data = [
//    "statu" => "executed",
//    "transaction_id" => "49001598-9223-46b6-8f14-2e4a86fe35b5",
//    "order_id" => "order20221009200536",
//    "timestamp"=>"1665313546",
//    "app_key"=>"2de9204f931a045b5840454da33adb0e"
//];
//
//echo generateOBHash($data);

//if (function_exists('com_create_guid') === true)
//{
//    echo 999;
//}else{
//    echo 888;
//}

//function createGuid(): string
//{
//    if (function_exists('com_create_guid')) {
//        $guid=strtolower(trim(com_create_guid(), '{}'));
//    } else {
//        mt_srand((double)microtime(true) * 10000);
//        $charid = strtoupper(md5(uniqid(mt_rand(), true).get_mac() . getmypid() . getmypid()));
//        $hyphen = chr(45);
//        $guid = substr($charid, 0, 8) . $hyphen
//            . substr($charid, 8, 4) . $hyphen
//            . substr($charid, 12, 4) . $hyphen
//            . substr($charid, 16, 4) . $hyphen
//            . substr($charid, 20, 12);
//    }
//    return strtolower($guid);
//}
//
//function get_mac(){
//    static $mac_addr;
//    if (!empty($mac_addr)) return $mac_addr;
//
//    @exec("ifconfig -a", $return_array);
//    if (!$return_array){
//        foreach ( $return_array as $value )
//        {
//            if ( preg_match( "/[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f]/i", $value, $temp_array ) )
//            {
//                $mac_addr = $temp_array[0];
//                break;
//            }
//        }
//    }else{
//        $mac_addr = "";
//    }
//    return $mac_addr;
//}
//
//$guid_arr= [];
//for($i = 1;$i<=10;$i++){
//    $guid = createGuid();
//    if (in_array($guid,$guid_arr)){
//        echo $guid;
//    }
//    $guid_arr[] = $guid;
//}
//print_r($guid_arr);

//function combine_ob_hash($data, $secret){
//    $combined_str = $data["status"] . $data["transaction_id"] . $data["order_id"] . $data["timestamp"];
//    $hash_combined_str = hash('sha256', $combined_str);
//    $secret_hash_combined_str = hash('sha256', $hash_combined_str . $secret);
//    return $secret_hash_combined_str;
//}
//
//function generateOBHash($data){
//    $hash_tmp = $data["status"] . $data["transaction_id"] . $data["order_id"] . $data["timestamp"];
//    $hash_tmp2 = hash("sha256", $hash_tmp);
//    $hash_tmp2 = $hash_tmp2 . $data["app_key"];
//    $hash = hash("sha256", $hash_tmp2);
//    return $hash;
//}
//
//$secret = "b2fa38a3b6e124d97e6d6d81a7d11ca2";
//$data = [
//    'status' => "settled",
//    'transaction_id' => "4c2bf9ca-4dc7-54b5-f078-3860f15db90f",
//    'order_id'=>"Order1dd22345",
//    'timestamp'=>"1666777727",
//    'app_key'=>$secret
//];
//
//$hash_hpp = combine_ob_hash($data,$secret);
//$hash_webhook = generateOBHash($data);
//
//echo $hash_hpp.PHP_EOL;
//echo $hash_webhook.PHP_EOL;


function arrayRecursiveReplace($array, $targetArray)
{
    foreach ($targetArray as $key=>$val){
        if (is_array($val)){
            if (!isset($array[$key]) || empty($array[$key]))
                $array[] = $val;
            else
                $array[$key] = arrayRecursiveReplace($array[$key], $val);
        }else {
            if (!isset($array[$key]) || empty($array[$key])) $array[$key] = $val;
        }
    }

    return $array;
}

//$array = [
//    "customer_id"=>'',
//    'first_name'=>'me',
//    'shipping'=>[
//        'address_match'=>'',
//        'address_match2'=>'ahh'
//    ],
//];
//
//$targetArray = [
//    'customer_id' => '123456789123456789',
//    'first_name' => 'me1',
//    'billing' => [
//        'address' => [
//            'line_1' => '123123123',
//            'line_2' => '456456456',
//            'postcode' => '888999',
//            'addr' => [
//                'a'=>'123',
//                'b'=>'345',
//                'c'=>'678'
//            ]
//        ],
//        'email' => '1659413691@qq.com',
//        'phone' => '130'
//    ],
//    'shipping' => [
//        'address_match' => '77777',
//        'address_match2' => 'ahh1'
//    ],
//];

//$a = false;
//if ($a === ''){
//    echo 1;
//}else{
//    echo 2;
//}


//function createGuid($isLower = true): string
//{
//    if (function_exists('com_create_guid')) {
//        $guid = strtolower(trim(com_create_guid(), '{}'));
//    } else {
//        mt_srand((double)microtime(true) * 10000);
//        $charid = strtoupper(md5(uniqid(mt_rand(), true).self::getMac().getmypid()));
//        $hyphen = chr(45);
//        $guid = substr($charid, 0, 8).$hyphen
//            .substr($charid, 8, 4).$hyphen
//            .substr($charid, 12, 4).$hyphen
//            .substr($charid, 16, 4).$hyphen
//            .substr($charid, 20, 12);
//    }
//    if ($isLower) return strtolower($guid);
//    else return $guid;
//}
//
//function getMac()
//{
//    if (!empty(self::$macAddr)) {
//        return self::$macAddr;
//    }
//
//    @exec('ifconfig -a', $returnArray);
//    if (!$returnArray) {
//        foreach ($returnArray as $value) {
//            if (preg_match(
//                '/[0-9a-f][0-9a-f][:-]'.'[0-9a-f][0-9a-f][:-]'.'[0-9a-f][0-9a-f][:-]'.'[0-9a-f][0-9a-f][:-]'.'[0-9a-f][0-9a-f][:-]'.'[0-9a-f][0-9a-f]/i',
//                $value,
//                $tempArray
//            )) {
//                self::$macAddr = $tempArray[0];
//                break;
//            }
//        }
//    } else {
//        self::$macAddr = '';
//    }
//    return self::$macAddr;
//}


function gen_uuidv4() {
    $uuid = array(
        'time_low'  => 0,
        'time_mid'  => 0,
        'time_hi'  => 0,
        'clock_seq_hi' => 0,
        'clock_seq_low' => 0,
        'node'   => array()
    );

    $uuid['time_low'] = mt_rand(0, 0xffff) + (mt_rand(0, 0xffff) << 16);
    $uuid['time_mid'] = mt_rand(0, 0xffff);
    $uuid['time_hi'] = (4 << 12) | (mt_rand(0, 0x1000));
    $uuid['clock_seq_hi'] = (1 << 7) | (mt_rand(0, 128));
    $uuid['clock_seq_low'] = mt_rand(0, 255);

    for ($i = 0; $i < 6; $i++) {
        $uuid['node'][$i] = mt_rand(0, 255);
    }

    $uuid = sprintf('%08X-%04X-%04X-%02X%02X-%02X%02X%02X%02X%02X%02X',
                    $uuid['time_low'],
                    $uuid['time_mid'],
                    $uuid['time_hi'],
                    $uuid['clock_seq_hi'],
                    $uuid['clock_seq_low'],
                    $uuid['node'][0],
                    $uuid['node'][1],
                    $uuid['node'][2],
                    $uuid['node'][3],
                    $uuid['node'][4],
                    $uuid['node'][5]
    );

    return $uuid;
}

function uuid4(){
    return sprintf( '%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
        // 32 bits for "time_low"
                    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
                    mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
                    mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
                    mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
                    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}
//echo uuid4();


//$text = 'Hello1, World456!';
//
//$pattern = '/\d+/';
//
//$matches = array();
//
//if (preg_match('/\d+/', $text, $matches)) {
//    print_r($matches[0]);
//} else {
//    print_r($text);
//}

//$string = 'Hello1, World456!';
//$s = '1';
//
//if (strpos($string, $s) !== false) {
//    $result = substr($string, strpos($string, $s) + strlen($s));
//    $result = trim($result, ', !');  // 去掉前后空格和符号
//    echo $result;   // 输出：World456
//} else {
//    echo 'Not found';
//}
//
//$string = ',@ $   asfjhgghfs,!*';
//$result = preg_replace('/^[^\w]+|[^\w]+$/', '', $string);
//echo $result;   // 输出：asfjhgghfs


$myArray = [1, 2, 3];

function myFunction() {
    global $myArray;
    // 在函数内部可以访问 $myArray 数组
    print_r($myArray);
}

// 确保函数调用发生在数组定义之后

myFunction();
echo 1;
