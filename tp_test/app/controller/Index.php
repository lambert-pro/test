<?php

namespace app\controller;

use app\BaseController;
use app\common\HttpTrait;
use app\model\LetMeTest;


class Index extends BaseController
{
    use HttpTrait;

    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V'.\think\facade\App::version().'<br/><span style="font-size:30px;">16载初心不改 - 你值得信赖的PHP框架</span></p><span style="font-size:25px;">[ V6.0 版本由 <a href="https://www.yisu.com/" target="yisu">亿速云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ee9b1aa918103c4fc"></think>';
    }

    public function test()
    {
        $test = new LetMeTest();
        $result = $test->getList();
        print_r($result);die;
    }

    public function login($name = 'ThinkPHP6')
    {
        return 'login ,'.$name;
    }

    public function redisLock()
    {
        $lock = new RedisLock();
        $lock->lock();
        // do something，即需要被锁保护的代码
        // 被锁住的资源，其他节点无法访问和修改
        sleep(5);

        $lock->unlock();
    }

    public function redisLock1()
    {
        $lock = new RedisLock();
        $lock->lock();
        // do something，即需要被锁保护的代码
        sleep(2);
        $lock->unlock();
    }

    public function ssl()
    {
        $pem = '-----BEGIN RSA PRIVATE KEY-----
MIIEpQIBAAKCAQEA9/3EGB8W3ar1ZbB0DCtf6h5hD0C5bPv7p/ZMNYzsMEViQxs6
gkixlmdnUJIUR5AMaXFoFlihkMkhUOcevlkweUhv1V5RzHLQg6UVFWg0vKeyZaZ/
lbcQPDnKIueyTTToRtpOKORyRhPlSaN5UYAtFN3CoLzTK2Cxa290kOX3UJ0R/4ZU
2+Zm+amMRtMIFtWc00S5px81Ctn5AY1XEzY4rPUftdJTfZHBt5FdBt/ZoMnUTcIx
aul4kte2jarVVv0BdXbaW8ImYdVTJUmdA8M1PlQ8p3T71wRqXbdEootQp9sWV9pH
TRkF0ypShn16MN/5uaqEHmoAzz7yafjKeqej/wIDAQABAoIBAQC8iJCsRfZ8T5yA
0sVm+xLQSog/sFVIJcoMx5Loo1ps2FL78ZdptRpN3g8NkgEY5sqI307irj8mc8KA
XzVgQS45Bnj/HdXSOPeNHdQJkk+FnXhjD1Gv4JzXLJggMUW8rJxqQU1qiULXRAjt
EvsImwmq820kBmoEcF5x7yoPfsWm4kPPLVk3ZjH3+EW2w5Dn35T59E7+AFLqxZ41
JmP3fTgBPY8yzzM8zXYfzYDOqfLY0uhsyw4LGWSxNG0Huji/EknhfZ4i3TnXGW9z
9QBv0us61N9S77vrr/2F40ScMX5gcw+Gayv2nGhV4nDj62NYwZBiv8Ph9PCtPnvV
WRKsov0BAoGBAPzLLUnqwj/n5qGB17i2amZheqzm4z+3NJX88rVUf1RaKW9Enwwn
fTeGd3orJbWB1Uyj8dms9os+OWVszXiLNJVa4sL26VkY/smo11NgbR5+1+soq7Os
hPnA3jqLVUfJ5VbwLb7rlhIi+7R8xxLxN58xzI+G+MfuaGfUejvJgBFzAoGBAPsi
/uXsYEgggaeb/mUFaRdnDeimLWcMKYiIR6K+DPe7lYLQaDC/zENCWLDlCzEYg2dG
PYsXWsIyyJTjf85HiJTxRVYzDUS2aAOUl9WysTPHiqceB9lfDPUFlBgRq/NmgJl1
LvydpVIij+seHpuDNWDBqNBpHPGseZkdhpKb1VBFAoGAWbVccAPARV9tR9lFDYam
gYiMOTmCYYUJQ0TNeK3wtaV9WMAYVP7af87XLWKMcjoN0LHJTL8FiupdAfI3hFSa
J3pmSFvI+VZWbIffSfZJIu5Of2QicpOBaQQZmNsDO4OZQF3hTgRacDs76ZPyLXWu
kG7isfhq5sBjCp2rdvYN3aMCgYEAyrg3FhY2qkJDJq8PLTCu4ks3uQLbR4FTzXhk
iwPqp9buG0hrsl5AXlKiETjyTdFB0Q2sBCj4BCbGLxltQ3AO2lvf4nMXVM4BLFK4
NbImxGtgiwH8yASoCulT4BHzwWiOilFDensuxhxMHDiV8GZ7ofzxbjpLOPJGvchN
pu7PxBkCgYEAsx2gVd3ozE7VglPxczKToKEW5EJ7we5wOpocF2UseiVV1N4wiq52
Wh482baHogK5m5o5YsLA5J1BUx9/xh/dpYraCMWhGho9kWTJNzpx6TrC6Wx9tZXf
YEHerzsRYjPNMu4T64yholBaqBe4e+QyEYKWDDiXW/ZPxzk3zGFvW5E=
-----END RSA PRIVATE KEY-----';

        $privateKey = openssl_pkey_get_private($pem, '');
        print_r($privateKey);
        die;
    }

    public function customData()
    {
        //  检测文本中不能存在3-4和12-19位的连续数字 1234-1234-1234-1234 or 1234123412341234
        //  不能有'card', 'number', 'cardnumber', 'cvv', 'cvv2', 'cv2', 'cardno', 'cardnum', 'card-number', 'card-no', 'cnum', 'cardn'等字眼
        $customData = 'cad4427808001112223337-';

        //判断敏感词
        $sensitiveWords = ['card', 'number', 'cardnumber', 'cvv', 'cvv2', 'cv2', 'cardno', 'cardnum', 'card-number', 'card-no', 'cnum', 'cardn'];
        foreach ($sensitiveWords as $word) {
            if (preg_match("/[\s\S]*{$word}[\s\S]*/i", $customData)) {
                $sensitiveWord = $word;
                echo $word;
                die;
            }
        }

        // 判断卡号 cvv
        preg_match('/\d{12,19}|[\d\-]{19}|[\D]\d{3,4}[\D]/i', $customData, $matches);
        if (!empty($matches)) {
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
    }

    public function useTrait()
    {
        $id = $this->request->get('id');
        $url = 'https://api.example.com/users/'.$id;

        $result = get($url);
        return json_decode($result);
    }

    // Facade 门面 文档p50
    public function useFacade()
    {
        #使用实例化访问方法
//        $test = new Test();
//        echo $test->hello('TP!');

        echo "Currency ThinkPHP Version: ".\think\facade\App::version().PHP_EOL;
        echo \app\facade\Test::hello("TP2!").PHP_EOL;
    }


}
