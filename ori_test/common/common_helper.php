<?php

use Config\Services;
use CodeIgniter\Config\Factories;
use CodeIgniter\Database\ConnectionInterface;
use Common\Libraries\SwooleLib\Context;

/**
 * Getting common model instances from Factories
 * NOTE: 
 * 1. Please using the full name of common model, such as Common\Models\OAuthClientModel
 * 2. component helps to protect app model from common model when they return a shared instance of the class
 *
 * @param string $name
 * @param bool $getShared
 * @param ConnectionInterface|null $conn
 * @return mixed
 */
function commonModel(string $name, bool $getShared = true, ?ConnectionInterface &$conn = null)
{
    if (strpos($name, 'Common\\Models\\') !== 0) $name = 'Common\\Models\\' . $name;
    return Factories::models($name, ['component' => 'commonModel', 'preferApp' => false, 'getShared' => $getShared], $conn);
}


/**
 * More simple way of getting model instances from Factories
 * Support Swoole Corountine
 *
 * @return mixed
 */
function goModel(string $name, bool $getShared = true, ?ConnectionInterface &$conn = null)
{
    if (Context::isCoroutine()) {
        if ($getShared && !empty(Context::get($name))) {
            return Context::get($name);
        } else {
            $model = Factories::models($name, ['getShared' => false], $conn);
            Context::put($name, $model);
            return $model;
        }
    } else {
        return Factories::models($name, ['getShared' => $getShared], $conn);
    }
}

/**
 * Getting common model instances from Factories
 * Support Swoole Corountine
 * NOTE: 
 * 1. Please using the full name of common model, such as Common\Models\OAuthClientModel
 * 2. component helps to protect app model from common model when they return a shared instance of the class
 *
 * @param string $name
 * @param bool $getShared
 * @param ConnectionInterface|null $conn
 * @return mixed
 */
function commonGoModel(string $name, bool $getShared = true, ?ConnectionInterface &$conn = null)
{
    if (strpos($name, 'Common\\Models\\') !== 0) $name = 'Common\\Models\\' . $name;

    if (Context::isCoroutine()) {
        if ($getShared && !empty(Context::get($name))) {
            return Context::get($name);
        } else {
            $model = Factories::models($name, ['component' => 'commonModel', 'preferApp' => false, 'getShared' => false], $conn);
            Context::put($name, $model);
            return $model;
        }
    } else {
        return Factories::models($name, ['component' => 'commonModel', 'preferApp' => false, 'getShared' => $getShared], $conn);
    }
}

function getOAuthClient(array $oAuthInfo = []): \Common\Libraries\OAuthServer\OAuthClient
{
    return Services::oAuthClient($oAuthInfo);
}

function getController()
{
    $request = Services::request();
    $router = Services::router(null, $request, false);

    $path = uri_string(true);

    $controller = $router->handle($path);
    $method     = $router->methodName();

    return [
        'ctrl_name' => $controller,
        'ctrl_method' => $method,
    ];
}

function getCliController()
{
    $request = Services::clirequest();
    $router = Services::router(null, $request, false);

    $path = $request->getPath();

    $controller = $router->handle($path);
    $method     = $router->methodName();

    return [
        'ctrl_name' => $controller,
        'ctrl_method' => $method,
    ];
}

function getFullUri()
{
    $controller = getController();
    $ctrl_name = str_replace('\\App\\Controllers\\', '', $controller['ctrl_name']);
    $ctrl_name = str_replace('\\', '/', $ctrl_name);
    $full_uri = $ctrl_name . '/' . $controller['ctrl_method'];

    return $full_uri;
}

function logController(string $level, string $message, array $context = [])
{
    return Services::controllerLogger(true)
        ->log($level, $message, $context);
}

function logMethod(string $level, string $message, array $context = [])
{
    return Services::methodLogger(true)
        ->log($level, $message, $context);
}

function createGuid()
{
    if (function_exists('com_create_guid')) {
        $guid = trim(com_create_guid(), '{}');
    } else {
        mt_srand((float)microtime(true) * 10000);
        $charid = strtoupper(md5(uniqid(mt_rand(), true) . getMac() . getmypid()));
        $hyphen = chr(45);
        $guid = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);
    }
    return strtolower($guid);
}

function getMac()
{
    static $macAddr;
    if (!empty($macAddr)) {
        return $macAddr;
    }

    @exec("ifconfig -a", $returnArray);
    if (!$returnArray) {
        foreach ($returnArray as $value) {
            if (preg_match(
                "/[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f]/i",
                $value,
                $tempArray
            )) {
                $macAddr = $tempArray[0];
                break;
            }
        }
    } else {
        $macAddr = "";
    }
    return $macAddr;
}

function camelize($uncamelizedWords, $separator = '_')
{
    $uncamelizedWords = $separator . str_replace($separator, " ", strtolower($uncamelizedWords));
    return ltrim(str_replace(" ", "", ucwords($uncamelizedWords)), $separator);
}

function camelizeArrayField(&$array, $separator = '_')
{
    foreach ($array as $field => $value) {
        $camelizeField = camelize($field);

        if ($camelizeField !== $field) {
            $array[$camelizeField] = $value;
            unset($array[$field]);
        }

        if (is_array($value) && count(array_filter(array_keys($value), 'is_string')) > 0) {
            camelizeArrayField($array[$camelizeField], $separator);
        }
    }
}

function uncamelize($camelCaps, $separator = '_')
{
    return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
}

function uncamelizeArrayField(&$array, $separator = '_')
{
    foreach ($array as $field => $value) {
        $uncamelizeField = uncamelize($field);

        if ($uncamelizeField !== $field) {
            $array[$uncamelizeField] = $value;
            unset($array[$field]);
        }

        if (is_array($value)) {
            uncamelizeArrayField($array[$uncamelizeField], $separator);
        }
    }
}

function uncamelizeDataMap($data, &$mapData, $parentPath = '', $aliasInfo = [])
{
    foreach ($data as $field => $value) {
        $tmpParentPath = empty($parentPath) ? $field : $parentPath . "_" . $field;
        if (is_array($value)) {
            uncamelizeDataMap($value, $mapData, $tmpParentPath, $aliasInfo[$field] ?? []);
        } else {
            if (!empty($aliasInfo[$field])) {
                $tmpParentPath = $aliasInfo[$field];
            }

            $mapData[$tmpParentPath] = $value;
        }
    }
}

function uncamelizeDataUnmap($mapData, $aliasInfo = [])
{
    $data = [];

    foreach ($mapData as $field => $value) {
        if (!isset($aliasInfo[$field])) continue;

        $fieldAlias = explode('.', $aliasInfo[$field]);
        $dataTmp = &$data;
        foreach ($fieldAlias as $fa) {
            if (!isset($dataTmp[$fa])) $dataTmp[$fa] = [];
            $dataTmp = &$dataTmp[$fa];
        }
        $dataTmp = $value;
        #3543
        if ($aliasInfo[$field] == "created" || $aliasInfo[$field] == 'last_updated') {
            $ymd = date('Y-m-d', strtotime($value));
            $his = date('H:i:s', strtotime($value));
            $dataTmp = $ymd . 'T' . $his . 'Z';
        }
    }

    return $data;
}

function mapToBoolString($val)
{
    if (!empty($val)) {
        return 'true';
    } else {
        return 'false';
    }
}

function getTdsAuthenticationCategoryType($typeID)
{
    $authenticationCategoryArray = array(
        1 => 'PAYMENT', 2 => 'NON_PAYMENT',
    );

    if (!is_numeric($typeID)) {
        $authenticationCategoryArray = array_flip($authenticationCategoryArray);
        $typeID = strtoupper(trim($typeID));
    }

    return isset($authenticationCategoryArray[$typeID]) ? $authenticationCategoryArray[$typeID] : '';
}

function getTdsAuthenticationSourceType($typeId)
{
    $authenticationSourceArray = array(
        1 => 'BROWSER', 2 => 'STORED_RECURRING', //3 => 'MOBILE_SDK'
    );

    if (!is_numeric($typeId)) {
        $authenticationSourceArray = array_flip($authenticationSourceArray);
        $typeId = strtoupper(trim($typeId));
    }

    return isset($authenticationSourceArray[$typeId]) ? $authenticationSourceArray[$typeId] : '';
}

function getTdsColorDepthType($typeID)
{
    $colorDepthArray = array(
        1 => 'ONE_BIT', 2 => 'TWO_BITS', 4 => 'FOUR_BITS',
        8 => 'EIGHT_BITS', 15 => 'FIFTEEN_BITS', 16 => 'SIXTEEN_BITS',
        24 => 'TWENTY_FOUR_BITS', 30 => 'THIRTY_BITS', 32 => 'THIRTY_TWO_BITS', 48 => 'FORTY_EIGHT_BITS',
    );

    if (!is_numeric($typeID)) {
        $colorDepthArray = array_flip($colorDepthArray);
        $typeID = strtoupper(trim($typeID));
    }

    return isset($colorDepthArray[$typeID]) ? $colorDepthArray[$typeID] : '';
}

function getTdsAuthenticationRequestType($typeId)
{
    $authenticationRequestTypeArray = array(
        TDS_TYPE_CARDHOLDER_VERIFICATION_ID => 'CARDHOLDER_VERIFICATION',
        TDS_TYPE_PAYMENT_TRANSACTION_ID => 'PAYMENT_TRANSACTION',
        TDS_TYPE_RECURRING_TRANSACTION_ID => 'RECURRING_TRANSACTION',
        TDS_TYPE_ADD_CARD_ID => 'ADD_CARD',
        TDS_TYPE_MAINTAIN_CARD_ID => 'MAINTAIN_CARD',
        TDS_TYPE_INSTALMENT_TRANSACTION_ID => 'INSTALMENT_TRANSACTION'
    );

    if (!is_numeric($typeId)) {
        $authenticationRequestTypeArray = array_flip($authenticationRequestTypeArray);
        $typeId = strtoupper(trim($typeId));
    }

    return isset($authenticationRequestTypeArray[$typeId]) ? $authenticationRequestTypeArray[$typeId] : '';
}

function getTdsChallengeRequestIndicatorType($typeId)
{
    $challengeRequestIndicatorArray = array(
        0 => 'NO_PREFERENCE', 1 => 'NO_CHALLENGE_REQUESTED', 2 => 'CHALLENGE_PREFERRED', 3 => 'CHALLENGE_MANDATED',
    );

    if (!is_numeric($typeId)) {
        $challengeRequestIndicatorArray = array_flip($challengeRequestIndicatorArray);
        $typeId = strtoupper(trim($typeId));
    }

    return isset($challengeRequestIndicatorArray[$typeId]) ? $challengeRequestIndicatorArray[$typeId] : '';
}

function getTdsMethodUrlCompletionType($typeId)
{
    $methodUrlCompletionArray = array(
        1 => 'YES', 2 => 'NO', 3 => 'UNAVAILABLE',
    );

    if (!is_numeric($typeId)) {
        $methodUrlCompletionArray = array_flip($methodUrlCompletionArray);
        $typeId = strtoupper(trim($typeId));
    }

    return isset($methodUrlCompletionArray[$typeId]) ? $methodUrlCompletionArray[$typeId] : '';
}

function getTdsChallengeWindowSizeType($typeID)
{
    $challengeWindowSizeArray = array(
        1 => 'WINDOWED_250X400', 2 => 'WINDOWED_390X400', 3 => 'WINDOWED_500X600',
        4 => 'WINDOWED_600X400', 5 => 'FULL_SCREEN',
    );

    if (!is_numeric($typeID)) {
        $challengeWindowSizeArray = array_flip($challengeWindowSizeArray);
        $typeID = strtoupper(trim($typeID));
    }

    return isset($challengeWindowSizeArray[$typeID]) ? $challengeWindowSizeArray[$typeID] : '';
}

function istrue($val, $returnNull = false)
{
    $boolval = (is_string($val) ? filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : (bool) $val);
    return ($boolval === null && !$returnNull ? false : $boolval);
}

function formateDatetime($time = false, $formate = "Y-m-d H:i:s")
{
    return ($time > 0) ? date($formate, $time) : date($formate, time());
}

function sha256hash($param, $secret)
{
    if (isset($param['mid_id'])) {
        $id = $param['mid_id'];
    } else {
        $id = $param['company_id'];
    }
    if (in_array(strtoupper($param['transaction_type']), array('AUTH_ONLY', 'AUTH_CAPTURE', 'CREDIT', 'BENEFICIARY_NEW', 'PAY_OUT'))) {
        $str = $param['timestamp'] . $param['transaction_type'] . $id . $param['merchant_order_id'];
    } elseif (in_array(strtoupper($param['transaction_type']), array('CAPTURE', 'VOID', 'REFUND', 'ACCOUNT_UPDATER', 'SUBSCRIPTION_MANAGE'))) {
        $str = $param['timestamp'] . $param['transaction_type'] . $id . $param['original_transaction_id'];
    }
    return hash('sha256', $str . $secret);
}

function transactionSha256hash($param, $secret): string
{
    if (isset($param['mid_id'])) {
        $id = $param['mid_id'];
    } else {
        $id = $param['company_id'];
    }

    if (in_array(strtoupper($param['transaction']['transaction_type']), ['AUTH_ONLY', 'AUTH_CAPTURE', 'CREDIT', 'BENEFICIARY_NEW', 'PAY_OUT'])) {
        $str = $param['timestamp'] . $param['transaction']['transaction_type'] . $id . $param['transaction']['merchant_order_id'];
    } elseif (in_array(strtoupper($param['transaction']['transaction_type']), ['CAPTURE', 'VOID', 'REFUND', 'ACCOUNT_UPDATER', 'SUBSCRIPTION_MANAGE'])) {
        $str = $param['timestamp'] . $param['transaction']['transaction_type'] . $id . $param['transaction']['original_transaction_id'];
    }

    return hash('sha256', $str . $secret);
}

function checkAlphanumeric($text)
{
    if (preg_match('/^[\w&àáâäãåąćęèéêëìíîïłńòóôöõøùúûüÿýżźñçčšžÀÁÂÄÃÅĄĆĘÈÉÊËÌÍÎÏŁŃÒÓÔÖÕØÙÚÛÜŸÝŻŹÑßÇŒÆČŠŽ∂ð\ \,\.\'\-]+$/', $text)) {
        return true;
    } else {
        return false;
    }
}

function checkAlpha($text)
{
    if (preg_match('/^[a-zA-Z&àáâäãåąćęèéêëìíîïłńòóôöõøùúûüÿýżźñçčšžÀÁÂÄÃÅĄĆĘÈÉÊËÌÍÎÏŁŃÒÓÔÖÕØÙÚÛÜŸÝŻŹÑßÇŒÆČŠŽ∂ð\ \,\.\'\-\␣]+$/', $text)) {
        return true;
    } else {
        return false;
    }
}

function checkCustomerDob($date)
{
    if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $date, $arr)) {
        return false;
    }

    if (!checkdate($arr[2], $arr[3], $arr[1])) {
        return false;
    }

    // strtotime has a bug in 32-bit operating system when the date is after 2038-1-19 03:14:07
    // $dob_time = strtotime($date);
    $obj = new DateTime($date);
    $dob_time = $obj->format("U");
    $now = strtotime('now');
    if ($dob_time >= $now) {
        return false;
    }

    $dob_date = date_create($date);
    $now_date = date_create(date("Y-m-d", $now));
    if (!$dob_date || !$now_date) {
        return false;
    }

    $live_date = date_diff($dob_date, $now_date);
    if (empty($live_date) || $live_date->days > 110 * 365) {
        return false;
    }

    return true;
}

function isUrl($url, $forceHttps = false)
{
    $url = strtolower($url);

    $httpType = '[s]?';
    if ($forceHttps) $httpType = 's';
    $result = preg_match("/^http{$httpType}:\/\/.+$/", $url);
    if (empty($result)) {
        return false;
    }

    $url = str_replace(array('http://', 'https://'), '', $url);
    if (stripos($url, ":/") !== false) {
        return false;
    }

    return true;
}

function isEmail($email)
{
    return preg_match('/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/', $email);
}

function isPhone($phone, $allowPlusSign = true)
{
    // #2528 Prod VS QA billing_phone - validation on the '+' sign
    $pattern = $allowPlusSign ? '/^([ ]|\(|\)|\+|\-|\d){7,20}$/' : '/^([ ]|\(|\)|\-|\d){7,20}$/';
    return preg_match($pattern, $phone);
}

function httpsRequest($url, $data, $header, $timeout = 30)
{
    $ch = curl_init();

    if (!empty($header)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    //  curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

    $result["data"] = curl_exec($ch);
    $result["curl_code"] = curl_errno($ch);
    $result["curl_errmes"] = curl_error($ch);
    $result["http_code"] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($result["curl_code"] > 0) {
        log_message("error", "(https request) curl ({$url}) error code: {$result["curl_code"]}, error message: {$result["curl_errmes"]}");
    }

    return $result;
}

function httpsTlsRequest($url, $data, $header, $pem, $timeout = 30)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
    curl_setopt($ch, CURLOPT_SSLCERT, realpath($pem));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $result["data"] = curl_exec($ch);
    $result["curl_code"] = curl_errno($ch);
    $result["curl_errmes"] = curl_error($ch);
    $result["http_code"] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    return $result;
}

function curlGet($url, $header, $timeout = 20)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $result["data"] = curl_exec($ch);
    $result["curl_code"] = curl_errno($ch);
    $result["curl_errmes"] = curl_error($ch);
    $result["http_code"] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    return $result;
}

function curlPut($url, $data, $header, $timeout = 30)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

    $result["data"] = curl_exec($ch);
    $result["curl_code"] = curl_errno($ch);
    $result["curl_errmes"] = curl_error($ch);
    $result["http_code"] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    return $result;
}

function getArrayKeyOrValue($array, $keyOrValue, $isUpperCase = false)
{
    if (!is_numeric($keyOrValue)) {
        $array = array_flip($array);
        $keyOrValue = trim($keyOrValue);
        if ($isUpperCase) $keyOrValue = strtoupper($keyOrValue);
    }

    return isset($array[$keyOrValue]) ? $array[$keyOrValue] : '';
}

function getAssociativeArrayKeyOrValue($array, $keyOrValue, $isFlip = false)
{
    if ($isFlip) {
        $array = array_flip($array);
    }

    return isset($array[$keyOrValue]) ? $array[$keyOrValue] : '';
}

function addTestingEnvSuffix(string $subject, ?string $projectName = null, bool $ignoreSuffix = false): string
{
    if ($ignoreSuffix || empty($subject) || !isTestingEnv()) {
        return $subject;
    }

    if ($projectName === null) {
        $projectName = defined('PROJECT_NAME') ? PROJECT_NAME : "";
    }

    $envShortName = [
        "development" => "DEV",
        "qa" => "QA",
        "production" => "PROD",
    ];

    $env = $envShortName[ENVIRONMENT];
    $serverName = getenv('MACHINE_NAME');
    $suffix = "[{$env}][$projectName]" . (empty($serverName) ? "" : "[{$serverName}]");
    return "{$subject} {$suffix}";
}

function isTestingEnv($contain_dev_env = TRUE)
{
    $result = FALSE;
    switch (ENVIRONMENT) {
        case 'development':
            $result = $contain_dev_env;
            break;
        case 'testing':
        case 'qa':
            $result = TRUE;
            break;
        case 'production':
            $result = FALSE;
            break;
    }

    return $result;
}

function sendSlack(array $config = [], array $fields = [], bool $ignoreSuffix = false)
{
    /** @var \Common\Libraries\Slack\Slack */
    $slack = service("Slack");

    if (isset($config["botName"])) {
        $config["botName"] = addTestingEnvSuffix($config["botName"], null, $ignoreSuffix);
    }

    $slack->configure($config);

    $slack->setFields($fields);

    return $slack->send();
}

/**
 * Send task to Swoole Server
 * If can not send, the run the task on local
 *
 * @param array $taskData
 * @param string $type
 * @return void
 */
function sendSwooleTask(array $taskData, string $type)
{
    $isRunOnSwooleServer = false;

    /** @var \Common\Libraries\SwooleLib\Client */
    $swooleLibClient = service("swooleLibClient");
    if ($swooleLibClient->connectServer()) {
        if ($swooleLibClient->sendTask($type, $taskData)) {
            $isRunOnSwooleServer = true;
            logController("debug", "Run {$type} task on Swoole Server...");
        }
    }

    if (!$isRunOnSwooleServer) {
        logController("error", "Run {$type} task on Swoole Server error: " . json_encode($swooleLibClient->addDataTaskType($taskData, $type)));
    }
}

/**
 * Send task to Queue of Redis Stream
 * If can not send, the run the task on local
 *
 * @param array $taskData
 * @param string $type
 * @return void
 */
function sendQueueTask(array $taskData, string $type)
{
    $isRunOnQueue = false;

    /** @var \Common\Libraries\SwooleLib\QueueProducerRedis */
    $producerRedis = service("queueProducerRedis");
    if ($producerRedis->sendTask($type, $taskData)) {
        $isRunOnQueue = true;
        logController("debug", "Run {$type} task on Queue of Redis Stream...");
    }

    if (!$isRunOnQueue) {
        logController("error", "Run {$type} task on Queue of Redis Stream error: " . json_encode($producerRedis->addDataTaskType($taskData, $type)));
    }
}

//From A timezone to B timezone;
function convertTimezone($timeStr, $from, $to, $format = 'Y-m-d H:i:s')
{
    try {
        $from = new DateTimeZone($from);
        $to = new DateTimeZone($to);

        $dateTime = new DateTime($timeStr, $from);
        $dateTime->setTimezone($to);
        return $dateTime->format($format);
    } catch (Exception $e) {
        return $timeStr;
    }
}

//From system timezone to client timezone;
function toClientTimezone($timeStr, $format = 'Y-m-d H:i:s')
{
    return convertTimezone($timeStr, date_default_timezone_get(), getOAuthClient()->getTimezone(), $format);
}

//From client timezone to system timezone;
function toSystemTimezone($timeStr, $format = 'Y-m-d H:i:s')
{
    return convertTimezone($timeStr, getOAuthClient()->getTimezone(), date_default_timezone_get(), $format);
}

/**
 * Boolean will be changed to 0/1 because of CI xss_clean
 * So use this function to check boolean instead of is_bool
 */
function isBoolXss($val)
{
    if ($val === '' || $val === '1') {
        return TRUE;
    } else {
        return FALSE;
    }
}

function fileBase64Size($fileBase64)
{
    $fileBase64 = preg_replace('/^(data:\s*(\w+\/\w+);base64,)/', '', $fileBase64);
    $fileBase64 = str_replace('=', '', $fileBase64);
    $file_len = strlen($fileBase64);
    $file_size = $file_len - ($file_len / 8) * 2;

    $file_size = round(($file_size / 1024), 2);

    return $file_size;
}

function checkFileBase64($fileBase64, $mime = false, $maxSize = false)
{
    if (preg_match('/^(data:\s*(\w+\/\w+);base64,)/', $fileBase64, $matches)) {
        $fileMime = $matches[2];
        if ($mime !== false && $mime != $fileMime) {
            return false;
        }

        if ($maxSize !== false) {
            $fileBase64 = str_replace($matches[1], '', $fileBase64);
            $file_size = fileBase64Size($fileBase64);
            if ($file_size > $maxSize) {
                return false;
            }
        }
    } else {
        return false;
    }

    return true;
}

function isUuid($str)
{
    return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $str);
}

function nullToEmpty($str)
{
    return is_null($str) ? '' : $str;
}

function isAccountName($accountName)
{
    return mb_strlen($accountName, "utf-8") <= 50 && preg_match('/^([A-Za-z0-9\.\,\-\'])*$/', $accountName);
}

function isUkAccountNumber($accountNumber)
{
    return preg_match('/^([0-9]){8}$/', $accountNumber);
}

function isUkSortCode($sortCode)
{
    return preg_match('/^([0-9]){6}$/', $sortCode);
}

function isEuAccountNumber($accountNumber)
{
    return preg_match('/^([A-Za-z0-9]){8,34}$/', $accountNumber);
}

function isEuSortCode($sortCode)
{
    return preg_match('/^([A-Za-z0-9\-]){6,11}$/', $sortCode);
}

function isAmount($amount)
{
    return preg_match('/^(([1-9]{1}\d{0,7})|([0]{1}))(\.(\d){1,2})?$/i', $amount);
}

function isPayoutReference($reference)
{
    return preg_match('/^([a-zA-Z\d\-\+\/\?\:\.\#\=\!\*;@\s]){1,18}$/', $reference);
}

function parseMerchantRequest($merchantRequest)
{
    return json_decode(preg_replace('/ processed by \[([\w ]+)?\]/i', '', $merchantRequest), true);
}

function fullYear($date, $in_format = 'MMYY', $out_format = 'MMYYYY')
{
    if (!preg_match("/^\d{4}$/", $date)) {
        return $date;
    }

    $sub_date1 = substr($date, 0, 2);
    $sub_date2 = substr($date, 2, 2);

    $in_format = strtoupper($in_format);
    switch ($in_format) {
        case 'YYMM':
            $year = $sub_date1;
            $month = $sub_date2;
            break;
        case 'MMYY':
            $year = $sub_date2;
            $month = $sub_date1;
            break;
        default:
            return $date;
            break;
    }

    $cur_year = date('Y', time());
    $cur_century = substr($cur_year, 0, 2);

    $out_format = strtoupper($out_format);
    switch ($out_format) {
        case 'MMYYYY':
            $full_year = $month . $cur_century . $year;
            break;
        case 'YYYYMM':
        default:
            $full_year = $cur_century . $year . $month;
            break;
    }

    return $full_year;
}

function maskCardNumber($cardnumber)
{
    if (empty($cardnumber)) {
        return $cardnumber;
    }

    $cardnumberFirst6 = substr($cardnumber, 0, 6);
    $cardnumberLast4 = substr($cardnumber, -4);
    return concatMaskedCardNumber($cardnumberFirst6, $cardnumberLast4);
}

// #2633 American Express
// #2736 Show only the first 6 BIN digits
function concatMaskedCardNumber($cardnumberFirst6, $cardnumberLast4)
{
    if (empty($cardnumberFirst6) || empty($cardnumberLast4)) {
        return '';
    }

    $cardTypeId = forecastCardType($cardnumberFirst6);

    $maskedCardnumber = '';

    switch ($cardTypeId) {
        case CARD_TYPE_AMEX_ID:
            $maskedCardnumber = substr($cardnumberFirst6, 0, 6) . ' XXXXX ' . $cardnumberLast4;
            break;
        default:
            $maskedCardnumber = substr($cardnumberFirst6, 0, 4) . ' ' . substr($cardnumberFirst6, 4, 2) . 'XX XXXX ' . $cardnumberLast4;
            break;
    }

    return $maskedCardnumber;
}

function luhnCheck($cardNumber)
{
    if (empty($cardNumber) || !is_numeric($cardNumber)) {
        return false;
    }

    $sum = 0;
    $alt = false;
    $luhnNumber = (string)$cardNumber;

    for ($i = strlen($luhnNumber) - 1; $i >= 0; $i--) {
        if ($alt) {
            $temp = $luhnNumber[$i];
            $temp *= 2;
            $luhnNumber[$i] = ($temp > 9) ? $temp = $temp - 9 : $temp;
        }
        $sum += $luhnNumber[$i];
        $alt = !$alt;
    }

    return ($sum % 10) == 0;
}

function getCardTypeId($cardNumber)
{
    if (!luhnCheck($cardNumber)) {
        return 0;
    }

    $cardTypeIndex = 0;

    if (preg_match("/^4[0-9]{15,18}$/", $cardNumber)) {
        $cardTypeIndex = CARD_TYPE_VISA_ID; //Visa
    } elseif (preg_match("/^[25]{1}[0-9]{15,18}$/", $cardNumber)) {
        $cardTypeIndex = CARD_TYPE_MC_ID; //MasterCard
    } elseif (preg_match("/^3[47][0-9]{13}$/", $cardNumber)) {
        $cardTypeIndex = CARD_TYPE_AMEX_ID; //AmericanExpress
    } elseif (preg_match("/^6(?:011|5[0-9]{2})[0-9]{12}$/", $cardNumber)) {
        $cardTypeIndex = CARD_TYPE_DISCOVER_ID; //Discover
    } elseif (preg_match("/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/", $cardNumber)) {
        $cardTypeIndex = CARD_TYPE_DINERS_ID; //DinersClub
    } elseif (preg_match("/^(?:2131|1800|35\d{3})\d{11}$/", $cardNumber)) {
        $cardTypeIndex = CARD_TYPE_JCB_ID; //JCB
    } elseif (preg_match("/^(5020|5038|6304|6579|6761|6799)\d{12,15}$/", $cardNumber)) {
        $cardTypeIndex = CARD_TYPE_MAESTRO_ID; //maestro
    } elseif (preg_match("/^(6334|6767)\d{12,15}$/", $cardNumber)) {
        $cardTypeIndex = CARD_TYPE_SOLO_ID; //solo
    } elseif (preg_match("/^(4903|4905|4911|4936|6333|6759)\d{12,15}$/", $cardNumber)) {
        $cardTypeIndex = CARD_TYPE_SWITCH_ID; //switch
    } elseif (preg_match("/^(564182|633110)\d{10,13}$/", $cardNumber)) {
        $cardTypeIndex = CARD_TYPE_SWITCH_ID; //switch
    }

    return $cardTypeIndex;
}

function forecastCardType($cardNumber)
{
    $cardTypeIndex = 0;

    if (preg_match("/^4[0-9]{15,18}$/", $cardNumber)) {
        $cardTypeIndex = CARD_TYPE_VISA_ID; //Visa
    } elseif (preg_match("/^[25]{1}[0-9]{15,18}$/", $cardNumber)) {
        $cardTypeIndex = CARD_TYPE_MC_ID; //MasterCard
    } elseif (preg_match("/^3[47][0-9]{0,13}$/", $cardNumber)) {
        $cardTypeIndex = CARD_TYPE_AMEX_ID; //AmericanExpress
    } elseif (preg_match("/^6(?:011|5[0-9]{0,2})[0-9]{0,12}$/", $cardNumber)) {
        $cardTypeIndex = CARD_TYPE_DISCOVER_ID; //Discover
    } elseif (preg_match("/^3(?:0[0-5]|[68][0-9])[0-9]{0,11}$/", $cardNumber)) {
        $cardTypeIndex = CARD_TYPE_DINERS_ID; //DinersClub
    } elseif (preg_match("/^(?:2131|1800|35\d{0,3})\d{0,11}$/", $cardNumber)) {
        $cardTypeIndex = CARD_TYPE_JCB_ID; //JCB
    } elseif (preg_match("/^(5020|5038|6304|6579|6761|6799)\d{0,15}$/", $cardNumber)) {
        $cardTypeIndex = CARD_TYPE_MAESTRO_ID; //maestro
    } elseif (preg_match("/^(6334|6767)\d{0,15}$/", $cardNumber)) {
        $cardTypeIndex = CARD_TYPE_SOLO_ID; //solo
    } elseif (preg_match("/^(4903|4905|4911|4936|6333|6759)\d{0,15}$/", $cardNumber)) {
        $cardTypeIndex = CARD_TYPE_SWITCH_ID; //switch
    } elseif (preg_match("/^(564182|633110)\d{0,13}$/", $cardNumber)) {
        $cardTypeIndex = CARD_TYPE_SWITCH_ID; //switch
    }

    return $cardTypeIndex;
}

function checkCardCvv($cardCvv, $cardNumber = '')
{
    if (!preg_match('/^[0-9]{3,4}$/', $cardCvv)) {
        return false;
    }

    if (!empty($cardNumber)) {
        $cardTypeId = forecastCardType($cardNumber);

        switch ($cardTypeId) {
            case 3:
                if (strLen($cardCvv) != 4) return false;
                break;
            case 1:
            case 2:
            case 4:
            case 5:
                if (strLen($cardCvv) != 3) return false;
                break;
            default:
                break;
        }
    }

    return true;
}

function checkAccountCurrency($currency)
{
    return in_array(strtoupper($currency), array('GBP', 'EUR'));
}

function intervalPickup($arr, $length)
{
    $total = count($arr);
    if ($total <= $length) {
        return $arr;
    }

    $max_key = $total - 1;

    $mod = $total / $length;
    $new = array();
    $i = 0;
    while ($i < $total) {
        $n = round($i);
        if ($n > $max_key) {
            $n = $max_key;
        }

        $new[] = $arr[$n];
        $i = $i + $mod;
    }

    return $new;
}

function getDefenceStatus($statusID)
{
    $defence_status = array(
        '0' => 'noaction',
        '1' => 'defended',
        '2' => 'defended',
        '3' => 'defended',
        '4' => 'recovered',
        '5' => 'lost',
        '11' => 'pre_arb',
        '12' => 'challenge',
    );

    if (!is_numeric($statusID)) {
        $ids = array();
        foreach ($defence_status as $key => $value) {
            if ($value == $statusID) {
                $ids[] = $key;
            }
        }
        return implode(',', $ids);
    }

    return isset($defence_status[$statusID]) ? $defence_status[$statusID] : '';
}

function getTransactionType($typeId)
{
    $transactionType = [
        '1' => 'AUTH_ONLY',
        '2' => 'CAPTURE',
        '3' => 'AUTH_CAPTURE',
        '4' => 'VOID',
        '5' => 'REFUND',
        '6' => 'CREDIT',
        '7' => 'SUBSCRIPTION_MANAGE',
        '8' => 'BENEFICIARY_NEW',
        '9' => 'PAY_OUT',
        '10' => 'ACCOUNT_UPDATER',
    ];

    if (!is_numeric($typeId)) {
        $transactionType = array_flip($transactionType);
        $typeId = strtoupper(trim($typeId));
    }

    return isset($transactionType[$typeId]) ? $transactionType[$typeId] : '';
}

function getCardType($typeID)
{
    $card_type = array(
        '1' => 'VISA',
        '2' => 'MC',
        '3' => 'AMEX',
        '4' => 'DISCOVER',
        '5' => 'DINERS',
        '6' => 'JCB',
        '7' => 'MAESTRO',
        '8' => 'SOLO',
        '9' => 'SWITCH',
    );

    if (!is_numeric($typeID)) {
        $card_type = array_flip($card_type);
        $typeID = strtoupper(trim($typeID));
    }

    return isset($card_type[$typeID]) ? $card_type[$typeID] : '';
}

function percentage($a, $b)
{
    if ($b == 0) {
        return 0;
    }

    if ($a < 0) {
        return 0;
    }
    return round($a / $b * 10000) / 100;
}

function isMerchantCustomStr($merchantCustomStr)
{
    return mb_strlen($merchantCustomStr, "utf-8") <= 50 && preg_match('/^([A-Za-z0-9\.\,\-\_])*$/', $merchantCustomStr);
}

function fixCreateLedger($ledgerID)
{
    $ledgerID = $ledgerID ? $ledgerID : '';

    $function = 'accountFix';
    return exec('php ' . FCPATH . "index.php Cron Ledger {$function} {$ledgerID}");
}

function validIP($ip)
{
    $request = Services::request();
    return $request->isValidIP($ip);
}

function checkIPAddress($ipaddressList, $ipaddress)
{
    $systemConfig = config("System");
    $ipWhitelist = $systemConfig->ipWhitelist;
    if (!empty($ipWhitelist)) {
        if (empty($ipaddressList)) {
            $ipaddressList = $ipWhitelist;
        } else {
            $ipaddressList .= ',' . $ipWhitelist;
        }
    }

    if (!matchIP($ipaddress, $ipaddressList)) {
        return false;
    } else {
        return true;
    }
}

function matchIP($clientIP, $ipaddressList)
{
    if (empty($clientIP) || $clientIP == '0.0.0.0') {
        return false;
    }

    if (empty($ipaddressList)) {
        return false;
    }

    $ipaddressArr = explode(',', $ipaddressList);
    foreach ($ipaddressArr as $ipaddress) {
        $ipaddress = preg_replace("/\./", "\.", $ipaddress);
        $ipaddress = preg_replace("/\*/", "\d+", $ipaddress);
        if (preg_match("/$ipaddress/", $clientIP)) {
            return true;
        }
    }
    return false;
}

/**
 * Only allowed Array or string in JSON format
 */
function maskSensitiveObData($data, $returnString = true, $flags = 0)
{
    if (is_string($data)) {
        $dataArr = json_decode($data, true);
        if (empty($dataArr)) return $data;

        $data = $dataArr;
    }

    if (!empty($data)) maskSensitiveObDataArray($data);
    else return '';

    if ($returnString) $data = json_encode($data, $flags);

    return $data;
}

function maskSensitiveObDataArray(array &$data, string $parentKey = "root")
{
    foreach ($data as $key => $value) {
        if (is_array($value) && count(array_filter(array_keys($value), 'is_string')) > 0) {
            maskSensitiveObDataArray($data[$key], $key);
        }

        if (!is_string($value)) continue;

        //#3279 account_number not require encryption
        //if (in_array($key, ["account_number"])) {
        //    $data[$key] = maskAccountNumber($value);
        //}

        // domestic-payment-consents request data
        if ($parentKey == "CreditorAccount" || $parentKey == "Debtor") {
            if (in_array($key, ["Identification"])) {
                $data[$key] = maskAccountIdentification($value);
            }
        }

        if (in_array($key, ["client_secret"], true)) {
            $data[$key] = maskClientSecret($value);
        }
    }
}

function maskAccountNumber($accountNumber)
{
    $maskNumber = "####" . substr($accountNumber, 4, 4);
    // echo $accountNumber . " - " . $maskNumber;die;
    return $maskNumber;
}

function maskAccountIdentification($identification)
{
    $length = strlen($identification);
    $maskIdentification = "####" . substr($identification, 4, $length - 4);
    // echo $identification . " - " . $maskIdentification;die;
    return $maskIdentification;
}

function maskClientSecret($clientSecret)
{
    $length = strlen($clientSecret);
    $prefixLength = 4;
    $suffixLength = 4;
    if ($length < 12) {
        $prefixLength = 2;
        $suffixLength = 2;
    }
    $maskLength = $length - $prefixLength - $suffixLength;
    $maskClientSecret = substr($clientSecret, 0, $prefixLength) . str_pad('', $maskLength, '*') . substr($clientSecret, 0 - $suffixLength);
    // echo $identification . " - " . $maskIdentification;die;
    return $maskClientSecret;
}

function jsonEncodePretty($data, $isMask = true)
{
    if ($isMask) {
        $data = (array)$data;
        maskSensitiveObDataArray($data);
    }

    return json_encode($data, JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES);
}

/**
 * Only allowed Array or string in JSON format
 */
function maskSensitivePaymentData($data, $returnString = true, $flags = 0)
{
    if (is_string($data)) {
        $dataArr = json_decode($data, true);
        if (empty($dataArr)) return $data;

        $data = $dataArr;
    }

    if (!empty($data)) maskSensitivePaymentDataArray($data);
    else return '';

    if ($returnString) $data = json_encode($data, $flags);

    return $data;
}

function maskSensitivePaymentDataArray(array &$data, string $parentKey = "root")
{
    foreach ($data as $key => $value) {
        if (is_array($value) && count(array_filter(array_keys($value), 'is_string')) > 0) {
            maskSensitivePaymentDataArray($data[$key], $key);
        }

        if (!is_string($value) && !is_numeric($value)) continue;

        if ($parentKey == "card") {
            if (in_array($key, ["number"])) {
                $data[$key] = maskCardNumber($value);
            } else if (in_array($key, ["cvv"])) {
                $data[$key] = maskCvv($value);
            }
        }

        if (in_array($key, ["pan"])) {
            $data[$key] = maskCardNumber($value);
        }
    }
}

function maskCvv($cvv)
{
    return str_pad('', strlen($cvv), '#');
}

function redirectUrl($url)
{
    header("Location: " . $url);
    exit();
}

function getCardTypeName($cardTypeId, $isFullName = false)
{
    $cardTypeArr = array(
        CARD_TYPE_VISA_ID => 'VISA',
        CARD_TYPE_MC_ID => 'MC',
        CARD_TYPE_AMEX_ID => 'AMEX',
        CARD_TYPE_DISCOVER_ID => 'DISCOVER',
        CARD_TYPE_DINERS_ID => 'DINERS',
        CARD_TYPE_JCB_ID => 'JCB',
        CARD_TYPE_MAESTRO_ID => 'MAESTRO',
        CARD_TYPE_SOLO_ID => 'SOLO',
        CARD_TYPE_SWITCH_ID => 'SWITCH',
    );

    $cardTypeName = isset($cardTypeArr[$cardTypeId]) ? $cardTypeArr[$cardTypeId] : false;

    if (!$isFullName) {
        return $cardTypeName;
    } else {
        return getFullCardTypeName($cardTypeName);
    }
}

function getFullCardTypeName($shortName)
{
    $cardTypeArr = array(
        'MC' => 'MASTERCARD',
    );

    if (empty($shortName)) return $shortName;

    return isset($cardTypeArr[$shortName]) ? $cardTypeArr[$shortName] : $shortName;
}

function objectToArray($object)
{
    if (!empty($object)) {
        $arr = @(array)$object;
        foreach ($arr as &$value) {
            if (is_object($value)) {
                $value = objectToArray($value);
            }

            if (is_array($value)) {
                foreach ($value as &$val) {
                    if (is_object($val)) {
                        $val = objectToArray($val);
                    }
                }
            }
        }
        return $arr;
    } else {
        return '';
    }
}

function arrayToObject($arr)
{
    if (gettype($arr) != 'array') {
        return;
    }
    foreach ($arr as $k => $v) {
        if (gettype($v) == 'array' || getType($v) == 'object') {
            $arr[$k] = (object)arrayToObject($v);
        }
    }

    return (object)$arr;
}

function arrayPushAfterKey($array, $data = [], $key = false)
{
    $data  = (array)$data;
    $offset  = ($key === false) ? false : array_search($key, array_keys($array));
    $offset  = ($offset) ? $offset : false;
    if ($offset) {
        return array_merge(
            array_slice($array, 0, $offset),
            $data,
            array_slice($array, $offset)
        );
    } else {
        return array_merge($array, $data);
    }
}

/**
 * UTC Time Format - YYYY-mm-ddTHH:ii:ss[.xxxxxx][Z]
 */
function getUtcTime($timestamp = '', $isMicro = true, $microNum = 6, $timezoneOffset = false)
{
    $msecond = '';
    if ($isMicro) {
        $mtimestamp = !empty($timestamp) ? sprintf("%.{$microNum}f", $timestamp) : sprintf("%.{$microNum}f", microtime(true));
        $timestamp = floor($mtimestamp);
        $mtimestampArr = explode('.', $mtimestamp);
        $msecond = $mtimestampArr[1];
    } else {
        $timestamp = !empty($timestamp) ? $timestamp : time();
    }

    if ($timezoneOffset) {
        $timestamp = $timestamp - date('Z');
    }

    $utcTime = date('Y-m-d\TH:i:s', $timestamp);
    if ($msecond) {
        $utcTime = $utcTime . '.' . $msecond;
    }
    if ($timezoneOffset) {
        $utcTime = $utcTime . 'Z';
    }
    return $utcTime;
}

function getKek($version)
{
    $systemConfig = config("System");
    $url = $systemConfig->keyServer;
    $param = array(
        'user' => $systemConfig->keyServerUser,
        'password' => $systemConfig->keyServerPassword,
    );

    $ch = curl_init();
    log_message("error", $url . '?version=' . $version);
    curl_setopt($ch, CURLOPT_URL, $url . '?version=' . $version);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);

    $content = curl_exec($ch);
    if (empty($content)) {
        // throw_exception(906);
        return false;
    }

    $data = json_decode($content, true);

    if (empty($data) || empty($data['status']) || $data['status'] != 1 || empty($data['kek'])) {
        /** @var \Common\Models\SystemMessageModel */
        $systemMessageModel = model('SystemMessageModel');
        $msg = "Request key server not found KEK";
        $systemMessageModel->toEncryption($msg);
        // throw_exception(906);
        return false;
    }

    return $data['kek'];
}

function getDek($keyVersionId = null, $transactionId = null)
{
    /** @var \Common\Models\SystemMessageModel */
    $systemMessageModel = model('SystemMessageModel');
    /** @var \Common\Models\KeyVersionModel */
    $keyVersionModel = model('KeyVersionModel');

    if (!empty($keyVersionId)) {
        $key = $keyVersionModel->getDetail($keyVersionId);
    } else {
        if (empty($transactionId)) {
            $key = $keyVersionModel->getNewest();
        } else {
            $key = $keyVersionModel->getNewestByTransactionId($transactionId);
        }
    }

    if (empty($key)) {
        $msg = "Not found key info in the DB";
        $systemMessageModel->toEncryption($msg);
    }

    $systemConfig = config("System");
    $dekSegment = $systemConfig->dekStartSegment + $key['id'];
    $segment = new \Common\Libraries\SimpleSHM($dekSegment);
    $dek = $segment->read();

    if (empty($dek)) {
        $msg = "Not found DEK in the RAM";
        $systemMessageModel->toEncryption($msg);

        $kek = getKek($key['id']);
        if ($kek === false) return false;
        $AES = new \Common\Libraries\AES_Encryption($kek);
        $dek = $AES->decrypt(hex2bin($key['E-DEK']));
        $hash256dek = hash('sha256', $dek);

        if ($hash256dek != $key['H-DEK']) {
            $msg = "Get KEK decrypt E-DEK compare H-DEK failure";
            $systemMessageModel->toEncryption($msg);
            // throw_exception(906);
            return false;
        }
        $segment->write($dek);
    }

    $DEK_AES = new \Common\Libraries\AES_Encryption($dek);
    return $DEK_AES;
}

function getNewestKeyVersionId()
{
    static $keyVersionId;

    if (empty($keyVersionId)) {
        /** @var \Common\Models\KeyVersionModel */
        $keyVersionModel = model('KeyVersionModel');
        $keyVersion = $keyVersionModel->getNewest();
        $keyVersionId = $keyVersion["id"];
    }

    return $keyVersionId;
}

function dekEncrypt($string, $keyVersionId = null)
{
    if ($keyVersionId === null) {
        $keyVersionId = getNewestKeyVersionId();
    }

    $DEK_AES = getDek($keyVersionId);
    if ($DEK_AES === false) return false;
    return bin2hex($DEK_AES->encrypt($string));
}

function dekDecrypt($tokenString, $keyVersionId, $transactionId = null)
{
    $DEK_AES = getDek($keyVersionId, $transactionId);
    if ($DEK_AES === false) return false;
    return $DEK_AES->decrypt(hex2bin($tokenString));
}

function filterParameter(array $data, string $filter = '', bool $deleteUnknownKey = false, string $unknownAlert = ''): array
{
    if (empty($filter)) return $data;

    $parsedFilter = parseFilterString($filter);

    filterReplaceRecursive($parsedFilter, $data, $deleteUnknownKey, $unknownAlert);

    return $parsedFilter;
}

function parseFilterString(string $filter = ''): array
{
    if (empty($filter)) return [];

    $filterKeys = explode(',', $filter);

    $result = [];

    foreach ($filterKeys as $val) {
        $keyTmp = explode('.', $val);
        $dataTmp = &$result;
        foreach ($keyTmp as $kt) {
            if (!isset($dataTmp[$kt])) $dataTmp[$kt] = [];
            $dataTmp = &$dataTmp[$kt];
        }
        $dataTmp = '';
    }

    return $result;
}

function filterReplaceRecursive(array &$target, array $data, bool $deleteUnknownKey = false, string $unknownAlert = '')
{
    foreach ($target as $k => &$v) {
        if (is_array($v)) {
            if (isset($data[$k])) {
                filterReplaceRecursive($v, $data[$k], $deleteUnknownKey, $unknownAlert);
            } else {
                if ($deleteUnknownKey === true) unset($target[$k]);
                else {
                    if (!empty($unknownAlert)) $v = $unknownAlert;
                    continue;
                }
            }
        } else {
            if (isset($data[$k])) {
                $v = $data[$k];
            } else {
                if ($deleteUnknownKey === true) unset($target[$k]);
                else {
                    if (!empty($unknownAlert)) $v = $unknownAlert;
                    continue;
                }
            }
        }
    }
}

function currencyConversion($from, $to = "GBP", $input)
{
    /** @var \Common\Models\CurrencyExchangeModel */
    $currencyExchangeModel = model('CurrencyExchangeModel');
    $exchangeRate          = $currencyExchangeModel->getExchange($from, $to);
    return round(($input * $exchangeRate), 2);
}

function locateBacktrace(?array $debug = null): array
{
    $request = Services::request();
    if ($debug === null) {
        $ingoreIndex = 0;
        $debug = debug_backtrace();
    } else {
        // Don't ingore
        $ingoreIndex = -1;
    }

    $location = [
        "current_url" => current_url(),
        "client_ip" => $request->getIPAddress(),
        "user_agent" => $_SERVER['HTTP_USER_AGENT'] ?? "Unknown user_agent",
    ];

    $backtrace = [];

    $debugLen = count($debug);
    foreach ($debug as $index => $value) {
        if ($index == $ingoreIndex) continue;
        if (($index + 1) == $debugLen) break;

        $backtrace[] = 'File:' . ($value['file'] ?? 'Unknown file') . ' Function:' . $value['function'] . ' Line:' . ($value['line'] ?? 'Unknown line');
    }

    $location["backtrace"] = $backtrace;

    return $location;
}

function implodeStringArray(array $array, string $separator = ',', string $quotation = "'"): string
{
    return "{$quotation}" . implode("{$quotation}{$separator} {$quotation}", $array) . "{$quotation}";
}


function arrayRecursiveReplace($array, $targetArray)
{
    foreach ($targetArray as $key => $val) {
        if (is_array($val)) {
            if (!isset($array[$key]) || empty($array[$key]))
                $array[$key] = $val;
            else
                $array[$key] = arrayRecursiveReplace($array[$key], $val);
        } else {
            if (!isset($array[$key]) || $array[$key] === '') $array[$key] = $val;
        }
    }

    return $array;
}

function isGuid($guid)
{
    return !empty($guid) && preg_match('/^([A-Za-z0-9\-]){36}$/', $guid);
}

function saveCvvOfCard($cardId, $cvv, $keyVersionId = null)
{
    /** @var \Common\Models\CvvModel */
    $cvvModel = model('CvvModel');

    if ($keyVersionId === null) {
        $keyVersionId = getNewestKeyVersionId();
    }

    $cvvToken = dekEncrypt($cvv, $keyVersionId);
    $cvvModel->saveOfCard($cardId, $cvvToken, $keyVersionId);
}

function getCvvOfCard($cardId)
{
    /** @var \Common\Models\CvvModel */
    $cvvModel = model('CvvModel');

    $cvv = $cvvModel->readOfCard($cardId);
    if (empty($cvv)) {
        return '';
    }

    return dekDecrypt($cvv->cvv_token, $cvv->key_version_id);
}

function saveCvvOfPayment($paymentGuid, $cvv, $keyVersionId = null)
{
    /** @var \Common\Models\CvvModel */
    $cvvModel = model('CvvModel');

    if ($keyVersionId === null) {
        $keyVersionId = getNewestKeyVersionId();
    }

    $cvvToken = dekEncrypt($cvv, $keyVersionId);
    $cvvModel->saveOfPayment($paymentGuid, $cvvToken, $keyVersionId);
}

function getCvvOfPayment($paymentGuid)
{
    /** @var \Common\Models\CvvModel */
    $cvvModel = model('CvvModel');

    $cvv = $cvvModel->readOfPayment($paymentGuid);
    if (empty($cvv)) {
        return '';
    }

    return dekDecrypt($cvv->cvv_token, $cvv->key_version_id);
}

function getTdsScaCompleteReason($typeId)
{
    if (strpos($typeId, 'SCHEME_STATUS_REASON') === 0) $typeId = 'SCHEME_STATUS_REASON';

    $methodUrlCompletionArray = array(
        1 => 'CARD_AUTHENTICATION_FAILED', 2 => 'UNKNOWN_DEVICE', 3 => 'UNSUPPORTED_DEVICE', 4 => 'EXCEEDS_AUTHENTICATION_FREQUENCY_LIMIT', 5 => 'EXPIRED_CARD', 6 => 'INVALID_CARD_NUMBER',
        7 => 'INVALID_TRANSACTION', 8 => 'NO_CARD_RECORD', 9 => 'SECURITY_FAILURE', 10 => 'STOLEN_CARD', 11 => 'SUSPECTED_FRAUD', 12 => 'TRANSACTION_NOT_PERMITTED_TO_CARDHOLDER',
        13 => 'CARDHOLDER_NOT_ENROLLED_IN_SERVICE', 14 => 'TRANSACTION_TIMED_OUT_AT_THE_ACS', 15 => 'LOW_CONFIDENCE', 16 => 'MEDIUM_CONFIDENCE', 17 => 'HIGH_CONFIDENCE', 18 => 'VERY_HIGH_CONFIDENCE',
        19 => 'EXCEEDS_ACS_MAXIMUM_CHALLENGES', 20 => 'NON_PAYMENT_TRANSACTION_NOT_SUPPORTED', 21 => 'THREERI_TRANSACTION_NOT_SUPPORTED', 22 => 'ACS_TECHNICAL_ISSUE', 23 => 'DECOUPLED_AUTHENTICATION_REQUIRED_BY_ACS', 24 => 'DECOUPLED_MAX_EXPIRY_TIME_EXCEEDED', 25 => 'INSUFFICIENT_TIME_TO_AUTHENTICATE', 26 => 'AUTHENTICATION_ATTEMPTED_BUT_NOT_PERFORMED',
        27 => 'SCHEME_STATUS_REASON', // Scheme_Status_Reason## , "##" will be replaced by numerical value in GP’s response, we must ensure to pass this in the API response
        28 => 'ERROR_RECEIVED_DOWNSTREAM',
    );

    if (!is_numeric($typeId)) {
        $methodUrlCompletionArray = array_flip($methodUrlCompletionArray);
        $typeId = strtoupper(trim($typeId));

        return isset($methodUrlCompletionArray[$typeId]) ? $methodUrlCompletionArray[$typeId] : 1;
    } else {
        return isset($methodUrlCompletionArray[$typeId]) ? $methodUrlCompletionArray[$typeId] : '';
    }
}

function getPaymentMethod($typeId)
{
    $paymentMethodArray = array(
        1 => 'authorisation',
        2 => 'apple_pay',
        3 => 'google_pay',
        4 => 'refund',
        5 => 'void',
        6 => 'visa_direct',
        7 => 'mastercard_send'
    );

    if (!is_numeric($typeId)) {
        $paymentMethodArray = array_flip($paymentMethodArray);
        $typeId = trim($typeId);

        return isset($paymentMethodArray[$typeId]) ? $paymentMethodArray[$typeId] : 1;
    } else {
        return isset($paymentMethodArray[$typeId]) ? $paymentMethodArray[$typeId] : '';
    }
}

function isMerchantCustomData($merchantCustomData)
{
    return mb_strlen($merchantCustomData, 'utf-8') <= 50 && preg_match('/^([A-Za-z0-9\.\,\-\_])*$/', $merchantCustomData);
}



