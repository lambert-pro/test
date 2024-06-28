<?php

namespace app\controller;

use think\facade\Db;
use think\facade\Http;

class Epubit
{
    public array $header;
    public function __construct()
    {
        $this->header = [
           'Cookie'=>"gr_user_id=74ff0df5-4caa-4dbc-9d56-7db3f6ee3f4a; gr_user_id=74ff0df5-4caa-4dbc-9d56-7db3f6ee3f4a; b59341ccb802cc42_gr_last_sent_cs1=user_id:a97e9f72-0b86-4600-94f7-31db14296c9d; b59341ccb802cc42_gr_cs1=user_id:a97e9f72-0b86-4600-94f7-31db14296c9d; b59341ccb802cc42_gr_last_sent_cs1=user_id:a97e9f72-0b86-4600-94f7-31db14296c9d; Hm_lvt_8ff3cd19706f65b6b82041b04c9270ba=1715861965; Hm_lvt_1e6ee218f5d7b8801b81556440a4e79f=1715861965; Hm_lvt_335dd6fa5d923b403ef636de92768dea=1715861965; acw_tc=2760777317158756828761008e4752ae1c89e99b9c0565d20b123b7310c1de; b59341ccb802cc42_gr_session_id=0aad5457-66b4-42a2-a413-94ef0ca496bf; b59341ccb802cc42_gr_last_sent_sid_with_cs1=0aad5457-66b4-42a2-a413-94ef0ca496bf; b59341ccb802cc42_gr_session_id_sent_vst=0aad5457-66b4-42a2-a413-94ef0ca496bf; Hm_lpvt_8ff3cd19706f65b6b82041b04c9270ba=1715876291; b59341ccb802cc42_gr_cs1=user_id:a97e9f72-0b86-4600-94f7-31db14296c9d; Hm_lpvt_1e6ee218f5d7b8801b81556440a4e79f=1715876292; Hm_lpvt_335dd6fa5d923b403ef636de92768dea=1715876292; SESSION=7ac92846-67dc-470d-bcf9-f1874467f967",
            'Origin-Domain' => 'www.epubit.com',
        ];
    }

    // http://tp.test.acquired.com/epubit/index
    // 获取异步书籍中存在7天vip的书籍数据，写入数据库
    public function index()
    {
        set_time_limit(60*5); // 设置最大执行时间为60秒

        $start = time();
        $client = new \GuzzleHttp\Client();

        //https://www.epubit.com/pubcloud/content/front/portal/getOutBuyInfo?code=UBda9e004244a0
//        $validateIdUri = 'https://www.epubit.com/pubcloud/content/front/portal/getOutBuyInfo?code=UBda9e004244a0';
//        $validateIdResponse = $client->request('GET', $validateIdUri,['headers'=>$this->header]);
//        $validateIdResponse = json_decode($validateIdResponse->getBody(),true);

//        $projectCodeUri = "https://www.epubit.com/pubcloud/content/front/downloadResourceProject/getAll/UBda9e004244a0";
//        $projectCodeResponse = $client->request('GET', $projectCodeUri,['headers'=>$this->header]);
//        $projectCodeResponse = json_decode($projectCodeResponse->getBody(),true);
//
//        //https://www.epubit.com/pubcloud/content/front/downloadResourceProject/getDownloadLink
//        $getLinkUri = "https://www.epubit.com/pubcloud/content/front/downloadResourceProject/getDownloadLink";
//        $getLinkRequestData = [
//            'projectCode' => $projectCodeResponse['data'][0]['code'],
//        ];
//        $getLinkResponse = $client->post($getLinkUri,['headers'=>$this->header,'json'=>$getLinkRequestData]);
//        $getLinkResponse = json_decode($getLinkResponse->getBody(),true);


        //https://www.epubit.com/pubcloud/content/front/portal/getUbookList?page=1&row=20&dateSort=desc&startPrice=&endPrice=&tagId=
        $pageStart = 1;
        $pageEnd = 20;
        for($i = $pageStart; $i<= $pageEnd; $i++){
            $uri = "https://www.epubit.com/pubcloud/content/front/portal/getUbookList?page=".$i."&row=20&dateSort=desc&startPrice=&endPrice=&tagId=";

            $response = $client->request('GET', $uri,['headers'=>$this->header]);
            $response = json_decode($response->getBody(),true);

            if (empty($response['data']['records'])){ continue;}

            foreach ($response['data']['records'] as  $val){
                if (empty($val['code'])){continue;}
                //https://www.epubit.com/pubcloud/content/front/portal/getUbookDetail?code=UBda656cdcc18a
                $detailUri = 'https://www.epubit.com/pubcloud/content/front/portal/getUbookDetail?code='.$val['code'];

                $detailResponse = $client->request('GET', $detailUri,['headers'=>$this->header]);
                $detailResponse = json_decode($detailResponse->getBody(),true);
                if (empty($detailResponse['data']) || empty($detailResponse['data']['publicationCode'])){continue;}

                //判断 资源下载 是否显示，显示的书籍为有vip的
                //https://www.epubit.com/pubcloud/content/front/portal/isShowOutBuy?code=UB836238e7a9d3d
                $showOutBuyUri = "https://www.epubit.com/pubcloud/content/front/portal/isShowOutBuy?code=".$val['code'];
                $outBuyResponse = $client->request('GET', $showOutBuyUri,['headers'=>$this->header]);
                $outBuyResponse = json_decode($outBuyResponse->getBody(),true);
                if (empty($outBuyResponse)||empty($outBuyResponse['data'])||$outBuyResponse['data']['show'] != 1
                    ||$outBuyResponse['data']['showName'] !='资源下载'){continue;}

                //获取验证id
                //https://www.epubit.com/pubcloud/content/front/portal/getOutBuyInfo?code=UBda9e004244a0
                $validateIdUri = 'https://www.epubit.com/pubcloud/content/front/portal/getOutBuyInfo?code='.$val['code'];
                $validateIdResponse = $client->request('GET', $validateIdUri,['headers'=>$this->header]);
                $validateIdResponse = json_decode($validateIdResponse->getBody(),true);

                //https://www.epubit.com/pubcloud/content/front/portal/getOutBuyInfo?code=UBda9e004244a0
//                $outBuyInfoUri = 'https://www.epubit.com/pubcloud/content/front/portal/getOutBuyInfo?code='.$val['code'];
//                $outBuyInfoResponse = $client->request('GET', $outBuyInfoUri,['headers'=>$this->header]);
//                $outBuyInfoResponse = json_decode($outBuyInfoResponse->getBody(),true);
//                if (empty($outBuyInfoResponse) || empty($outBuyInfoResponse['data'])||empty($outBuyInfoResponse['data']['questionEntityList'])||$outBuyInfoResponse['data']['questionEntityList'][0]){continue;}
//
//                // 验证6位数数字 获取vip
//                //https://www.epubit.com/pubcloud/content/front/portal/loginOutBuy
//                $loginOutBuyUri = "https://www.epubit.com/pubcloud/content/front/portal/loginOutBuy";
//                $requestData = [
//                    'id' => $validateIdResponse['data']['id'],
//                    'questionEntityList' => [
//                        'id' => $outBuyInfoResponse['data']['questionEntityList'][0]['id'],
//                        'answer' => substr($detailResponse['data']['publicationCode'],-6),
//                        "questionDescription"=> "请输入“资源获取验证码”（6位数字）（纸书图书87页获取，电子书图书最后一页）",
//                        "seq"=> 1,
//                        "ubookCode"=> "UBda9e004244a0"
//                    ],
//                    "type"=> "dd"
//                ];
//                $loginOutBuyResponse = $client->post($loginOutBuyUri,['headers'=>$this->header,'json'=>$requestData]);
//                $loginOutBuyResponse = json_decode($loginOutBuyResponse->getBody(),true);
//
//                //获取projectCode
//                //https://www.epubit.com/pubcloud/content/front/downloadResourceProject/getAll/UBda9e004244a0
//                $projectCodeUri = "https://www.epubit.com/pubcloud/content/front/downloadResourceProject/getAll/".$val['code'];
//                $projectCodeResponse = $client->request('GET', $projectCodeUri,['headers'=>$this->header]);
//                $projectCodeResponse = json_decode($projectCodeResponse->getBody(),true);
//
//                //https://www.epubit.com/pubcloud/content/front/downloadResourceProject/getDownloadLink
//                $getLinkUri = "https://www.epubit.com/pubcloud/content/front/downloadResourceProject/getDownloadLink";
//                $getLinkRequestData = [
//                    'projectCode' => $projectCodeResponse['data'][0]['code'],
//                ];
//                $getLinkResponse = $client->post($getLinkUri,['headers'=>$this->header,'json'=>$getLinkRequestData]);
//                $getLinkResponse = json_decode($loginOutBuyResponse->getBody(),true);

                $isExist = Db::name('epubit')->where('name',$val['name'])->find();
                if (!empty($isExist)){
                    break;
                }

                $data = [
                    'name' => $val['name'],
                    'authors' => $val['authors'],
                    'vipCode' => substr($detailResponse['data']['publicationCode'],-6),
                    'link' => 'https://www.epubit.com/bookDetails?id='.$val['code'].'&typeName=',
                    'book_id' => $validateIdResponse['data']['id'] ?? '',
                    'publish_date' => $val['publishDate']
                ];

                Db::name('epubit')->insert($data);

            }
        }
        echo 'success!! use time: '.time()-$start.'s.';die;
    }

}