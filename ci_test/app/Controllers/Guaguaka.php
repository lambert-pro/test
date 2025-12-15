<?php

namespace App\Controllers;

use App\Libraries\HttpTrait;

class Guaguaka extends BaseController
{
    use HttpTrait;
    public array $ggkRequestData = [
        'NcParams' => [
            'scene' => 'nc_card_h5',
            'sessionId' => "01Pq7fKQ1nPaq6G2tmtIf4kjsXuwFHPsRUXsv6evduozNO4cL0cIxw9wIFzL1dmxNzkON_Rs6RL9v6UQerp_dByYG3iwapUTTZO_gq_91o3WN8J2S_Wf9w8pKkBYazy6tvI_rK94ZATEW0MmFl4LiQCuzlyojMIby-MTHOWsVmUEE",
            'sig' => "05XqrtZ0EaFgmmqIQes-s-CHNlVNMao3pPSRMIYBaoLrIKT7PKYU8CLz1PzhRxyWxLFFuR9YG-7XOCql31CtRpYH1iu4DRys2Tf747q95MdPNsDLiuTiW-Aocs7Kdry5O7DrjqNsCQJSAfBx2MwcuPoWef7911nhZaFoe1qOTjtWSxXL2bY5SdKXTQDzlcpH-F6iB7X1xYZRVJ3JEdeE2ona3AI15Z6Xy48VyB7A_kfz0OLvLYpN-4nbz13WVc8EqR7bjLKekg2yAo_bdnwyGGbAL8eYud4AFpnqegCfE7FTh20WZoqhHbzmcl8ie9xCsQOnyv2G63NpC0YbLY3wv3A2GqcQjoxDlecU084TkyCEw9GRUtZs-M-Bz-Q_T5B3UE6jur0S6BztCHCRXlJzOGjNq6q7wtvuaP5FFOa3x4dBU5e53IJ1mwMsuwlM4wRMsV",
            'token' => "FFFF0N00000000009EFC:nc_card_h5:1693209934727:0.4263223099708149"
        ]
    ];
    public array $ggkHeaders = [
        'Content-Type' => 'application/json',
        'Authorization' => 'bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJiaW5iaW5fNzkwNyIsImp0aSI6IjY0MTY3MiIsIlVzZXJJZCI6IjY0MTY3MiIsImlhdCI6IjIwMjMvOC8yOCA1OjI0OjMzIiwibmJmIjoxNjkzMjAwMjczLCJleHAiOjE2OTU3OTIyNzMsImlzcyI6Iml0dXJpbmciLCJhdWQiOiJpdHVyaW5nIiwicm9sZXMiOltdfQ.8g9MabYGKfVp3PsnCSOjza813URWKF4e0zCM3LdtwDU',
        'Cookie' => 'ASP.NET_SessionId=he0k1tsq43ldaityvqfrst50; __RequestVerificationToken=GsTx3dAX1MVVRG_ib3y-x3nOMAgZfcicYhkkCNFiXSZnFGDgwzbtGKbV42MH9j_nCrh9d27hnJLPaXtqJvyZ6PyHZi9srGrpKz05wH3AkDQ1; _ga=GA1.3.1178077145.1691388032; _gid=GA1.3.1393480209.1693184715; .AspNetCore.Session=CfDJ8MMjf%2BcrwbJAmoNtbv2GwmK4%2BZYRveLVciVQKdyCjMTr3I8ypnREy5VNqZjVRel0ivEX6384r11n51bJxeJer7KIGh6wGOqRAiBDQmdzsN2jgFh12fbCadiObREx1JGbBUkTQZWMWI6KSxMY4iC3YJGZFcXAR80GFgWN6iyfNaNJ; _gat=1; _ga_E87WFG4RGE=GS1.3.1693208380.32.1.1693209936.0.0.0',
        'Host' => 'api.ituring.com.cn',
        'Origin' => 'https://www.ituring.com.cn',
        'Referer' =>  'https://www.ituring.com.cn/',
        'Pragma' => 'no-cache'
    ];

    public function index()
    {
        return view('welcome_message');
    }

    public function ggkPost()
    {
        $get = $this->get('https://cf.aliyun.com/nocaptcha/analyze.jsonp?a=FFFF0N00000000009EFC&t=FFFF0N00000000009EFC%3Anc_card_h5%3A1693209934727%3A0.4263223099708149&n=226!6xrGsNt3blM4NbbvFbckzQWq2twZOGpzS3Pj25WCmfP3Jux3VDczwmyLA6CIntXDOrHzVCcd1BS9JD2gyMIjHc3EYvBDxY%2FYiBjqyT2MVX7LvMGzDr0%2FRCt2IWlc2dDg1sXMOr7p1wLL6M7gOsa5MH5SoZ1ol9ZhSFvmNvJBy4r%2FfIpuKgmFYIqnrBWZp%2BKeVcSaNMsDchzh468b1Gj7XRZ7yX%2FcqtLYFQNZqaxx4kWtTnw%2Bb%2BZu5RAU4zvHK%2FYoaKnfqnggIVza6LyodNBMi0PypxyfBCyiL7CGNyV%2BVJiJkyLFExJFLuR5NYEtoUZQO9CIj%2B%2FOW5%2FDLg7iFwwm%2BQUp4n7XvtXRE6tF9m%2BMBgieIKCPMH76%2BHkft3sX0xRV6eANE%2BW%2FTXU%2BeziRyLd%2FZfxS3VCy%2Bc7M4RRRuSxb6uzKG5JkzySmB0Ecg5IfjwjbK2t9nvOb01XuQZZBK6PiW87ao0Tjr4Tz6Wa%2BcKIHl%2FP6odb8rT3B1aKmJLAZH2QpOW8i8bhVvb3fe7k5T0d0ojIYD7DdAez2iiGoEcSx3agItylzy%2BWSdcMWWuf0drUsiTbxt4GCiR0wmBsOUrV1EzF4kWG5j%2Bth1K%2FGGE5t%2BFqyIMS1gSfaG1%2F3O1DWGrh8ASdy1watEgYc85ry20xuBkXgvD%2BBawFWWc3z9L%2FBpCDrPcRZLiuT7J3wM7nRg02gUbG%2BmpArS1VjXA65AGi20xg8uzJG%2B2dl8nIblF7530PRd%2FwjxlDokp6yD97gRmzfKz%2F7dVyGOADjtz7Gm%2BNiMHWF3gFksp48AjZj%2FGAR7MZlR%2Fywem3Fb0Bx0PEpFby87F2UeJA7vbM%2Bm%2FQMY4Njh12uGfPb2h3T9CyQAXx7n3yRdJKWpquc6P6ZsUpFo4ojvFGD%2BOGinsAPdSLZwaSKErN1pJuqyXrUi6jQAY4SjU8WRB9hupvsk1qQTTU80LKNGIqydSdyMyAt6Loq%2B4OTMUIoZYzrvl%2FGFLru%2BSeT0JloStUTWPyjGK2Tm3%2BB6CW%2Fs3wwUA965ejvg3hbbSouGQcF%2FniLRitsb%2BcnvQUJbZlMM9acUFNwKvr15VuyAa43VfrYEY6skiEjXDNNvShIDTqC37on%2BkcnDg7S%2FRD%2BBwNOgcx5aeXzTIDyUtucvu3IDPV%2B43gzW6TwgUfXT0Oc3DbJBkEDIWffbmLiYA1EKnpgiGHlvotMiox1uljCuCkeW4mC8Ub2pEhCmuHbZ7NtzNwE28%2BIgE7IpGa6r8qJwCM0Oq4wAPpvmBxiZ8uhoyueNoLUOrdDWq9A4%2Bp090rPqby%2F%2FiqY8f4HBNANvX2FJxgqwrBI53TkSVnBM5NncWBU3oXbfzhGvIs4z9yMafzpZO5WjP6DOmxDlsfpYtwoju7UDkwCBciDsh%2FQ5uPR7GqWFgmNOUMQiNh5aAfhnXm7wLALO65FX%2FMFB%2BMrvjSab%2FGmnQVDYlPtbaphzm%2FoL7HFwXbZg8%2FZyUkaneTR7JhV%2FtrLzm%2Fo3DaNb0oraCN9pDiQgXoAYfzsM%2FEy8eIMWAT69hc4e1H%2F6U3quvvt38QlyVcczcSsUSkH9ugy9lyGbDJXbUnkOwWY0siu9ZtvotkzsDfJTaYyMDr%2Bu5ONDkDAeyhjnuldK%2FWeqv16nEC%2Fnbf%2ByOUtArTORUkquj6ENjkHXg0qQQeC9OnOQ5WZow1xRbOHs4%2Fnjt7H%2FRMQdaG5nsb5%2F0bDKiW3soRlJhdt1Xbl9Uf%2FdrSen50XeOuNj30ORvTquXlvh9EVpvM2I89YPzTGhZQXC8yCNKkhT%2BXo7z4edKrO28qDyUNEneQR3s%2B%2BSe5LzV2oLgjadnD2ge6VyD5f7e083boBqKkctuBT3LXNpXJ2gB%2F20uRGnJksXPQHo5OcT59W3U1gpXJZSdbyyvZd%2BtTX38QHy2TcT5QK1iV%2BpwdrI8%2FfyrTCMZHP7BQQqwvtk0%2FWl%2FvcdjbZgBeryDmarIBe3Y6Ux4mVzm%2FW3cr%2BdjznhLl2Ok7a%2BZQshGgTlUDhwo%2B0cKies5sb0rHjwZg15b5r1fQ1qDO5wjpukVE9q%2FwWvGXB%2Fybk5VQthf3lHIcczm%2BWzJGbJj%2FeTBx2ygwfny5swPkQqQ57lsf%2F4U0b%2FstFY%2B%2FhQvvQ%2FzheQcA5uJMNXa0x7DLTp3uGZcVY2ryDGVzDUxpCh5LvOoZkX2hkJIbDt67BdVLDrd89cd6RMtAl%2F3%2B6Z6797HN2LQ0eIQ9t5IUWiLUKzpDiaqJqM8qp9whi8LjzKnLrEfEIolPTBSJkNV6N872KHFoD5m2Oxi67XyZP9jIOzJljgX4EzOhX5qGDsEPFxGHKFbOPrKs%2FTOsiiPHpqVZAxZkJ9PHRlcMpoV5KXbyGaz%2B%2BjD34Mhr8wNtxNT5YTJ1RWpkRtFex4d3%2F0zpidq%2FxOPEh2e32dDdlpHSQNxr%2F8v96oDHp6Be7fET9Z%2FKoo1bjZE5hMLXmamlr%2Fr%2FE%2BSOJBGf1FaezpJRDdvQcheFHp%2BRWT7yuMun21%2B%2F14YgJe%2BGJgYfyVYODdrvEa%2F3m3RpGiFoRusIaTcBEgyEOPCaf%2BZTiSJ5m3B9SB6NzJkoOqSat91NQJSbfwCuBajx605RcZPsVku2%2BaMw6%2BT%2FbbsP80c6sJnhwo2DM9Flku7YSPUAAKUaRUzWhNNXpC4dnJ8nLvO6DDHf%2FGXo7jrLatz4eqpMsXd2fdwsjgIE66xyfswtYHxXJRnWwpxWf%2FPGIYBjwm8kKscA12lEtHTQ1&p=%7B%22ncbtn%22%3A%22617.5%7C225.1875%7C40%7C32%7C225.1875%7C257.1875%7C617.5%7C657.5%22%2C%22umidToken%22%3A%22GB74ADF5559853F3D8BE568C03744D71D901FCCE9F9D7A339A3%22%2C%22ncSessionID%22%3A%226ae4ba22ec02%22%7D&scene=nc_card_h5&asyn=0&lang=cn&v=1&callback=jsonp_020354275680194434');
        print_r($get);die;
        $url = 'https://api.ituring.com.cn/api/GuaGuaCode/Check';
        $this->ggkRequestData['code'] = 'SJDHYRKKS';
        log_message("error", 'request: '.json_encode($this->ggkRequestData));
        $result = $this->post($url, $this->ggkRequestData, $this->ggkHeaders);
        log_message('error', 'result: '.json_encode($result));
        var_dump($result);
        die;
    }

    public function generateStrings()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = 9;

        $startingCharacter = 'A';
        $totalCombinations = pow(strlen($characters), $length);

        // 设置文件路径
        $filePath = WRITEPATH.'a_start_strings.txt';

        // 打开文件以写入
        $file = fopen($filePath, 'w');
        if ($file) {
            for ($i = 0; $i < $totalCombinations; $i++) {
                $currentIndex = $i;
                $currentString = $startingCharacter;

                for ($j = 1; $j < $length; $j++) {
                    $currentString = $characters[$currentIndex % 26].$currentString;
                    $currentIndex = (int)($currentIndex / 26);
                }

                fwrite($file, $currentString.'.');
            }

            fclose($file);
            return "Strings starting with 'A' have been written to {$filePath}.";
        } else {
            return 'Error opening or writing to file.';
        }

    }
}
