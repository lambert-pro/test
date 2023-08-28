<?php

namespace App\Controllers;

use App\Libraries\HttpTrait;

class Guaguaka extends BaseController
{
    use HttpTrait;
    public array $ggkRequestData = [
        'NcParams' => [
            'scene' => 'nc_card_h5',
            'sessionId' => '01Pq7fKQ1nPaq6G2tmtIf4kjsXuwFHPsRUXsv6evduozO-axOUWs7RKEv5SJQRT4WH6zogyIvCf1fsyTt6JX3COVCMpWLoFE9ZT96q1H818ix8J2S_Wf9w8pKkBYazy6tvI_rK94ZATEW0MmFl4LiQCuzlyojMIby-MTHOWsVmUEE',
            'sig' => '05XqrtZ0EaFgmmqIQes-s-CHNlVNMao3pPSRMIYBaoLrIKT7PKYU8CLz1PzhRxyWxLTjHINEJnDjRGcRk9tWy5A2ZsOqFmFIRNcBY5bfTt_7FlHwYeuwSxwZeDKx3d5vilDrjqNsCQJSAfBx2MwcuPoWef7911nhZaFoe1qOTjtWQveffWUySr9EDeTIaFgpWZuAeK5hdpv0rCFtgCADRw7dBCeSAcaYb7efXBTeOXqEdu2TZuVRbmAFdIEfqp-kSogOG_McipaQC0VqlCWup3_AL8eYud4AFpnqegCfE7FTh20WZoqhHbzmcl8ie9xCsQOnyv2G63NpC0YbLY3wv3A0L6OBBH3jtpQkNHbPAticY9GRUtZs-M-Bz-Q_T5B3UEdiZbHZ1m9LzQf6aUXDShfq9uOT06Ij0Rjuwy3pSvT6HBPPizTX4qa4fYPameJm9r',
            'token' => 'FFFF0N00000000009EFC:nc_card_h5:1693201483506:0.7201415914025897'
        ]
    ];
    public array $ggkHeaders = [
        'Authorization' => 'bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJiaW5iaW5fNzkwNyIsImp0aSI6IjY0MTY3MiIsIlVzZXJJZCI6IjY0MTY3MiIsImlhdCI6IjIwMjMvOC8yOCA1OjI0OjMzIiwibmJmIjoxNjkzMjAwMjczLCJleHAiOjE2OTU3OTIyNzMsImlzcyI6Iml0dXJpbmciLCJhdWQiOiJpdHVyaW5nIiwicm9sZXMiOltdfQ.8g9MabYGKfVp3PsnCSOjza813URWKF4e0zCM3LdtwDU',
        'Cookie' => 'ASP.NET_SessionId=he0k1tsq43ldaityvqfrst50; __RequestVerificationToken=GsTx3dAX1MVVRG_ib3y-x3nOMAgZfcicYhkkCNFiXSZnFGDgwzbtGKbV42MH9j_nCrh9d27hnJLPaXtqJvyZ6PyHZi9srGrpKz05wH3AkDQ1; _ga=GA1.3.1178077145.1691388032; _gid=GA1.3.1393480209.1693184715; .AspNetCore.Session=CfDJ8MMjf%2BcrwbJAmoNtbv2GwmK4%2BZYRveLVciVQKdyCjMTr3I8ypnREy5VNqZjVRel0ivEX6384r11n51bJxeJer7KIGh6wGOqRAiBDQmdzsN2jgFh12fbCadiObREx1JGbBUkTQZWMWI6KSxMY4iC3YJGZFcXAR80GFgWN6iyfNaNJ; _ga_E87WFG4RGE=GS1.3.1693200251.30.1.1693201483.0.0.0',
        'Host' => 'api.ituring.com.cn',
        'Origin' => 'https://www.ituring.com.cn',
        'Referer' =>  'https://www.ituring.com.cn/'
    ];

    public function index()
    {
        return view('welcome_message');
    }

    public function ggkPost()
    {
        $url = 'https://api.ituring.com.cn/api/GuaGuaCode/Check';
        $this->ggkRequestData['code'] = 'SJDHYRKKS';
        $result = $this->post($url, $this->ggkRequestData, $this->ggkHeaders);
        var_dump($result);
        die;
    }

    public function generateStrings()
    {
        $strings = [];

        for ($i = 0; $i < 5; $i++) {
            $randomString = $this->generateRandomString();
            $strings[] = $randomString;
        }

        $url = 'https://api.ituring.com.cn/api/GuaGuaCode/Check';

        foreach ($strings as $code) {
            $this->ggkRequestData['code'] = $code;
            $result = $this->post($url, $this->ggkRequestData, $this->ggkHeaders);
            unset($this->ggkRequestData['code']);
            print_r($result);
        }

        return implode('<br>', $strings);
    }

    private function generateRandomString()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < 9; $i++) {
            $randomString .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
}
