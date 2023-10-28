<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use GuzzleHttp\Client;

class Epubit extends Command
{
    protected array $reuqestHeader = [
        "Cookie" => "gr_user_id=74ff0df5-4caa-4dbc-9d56-7db3f6ee3f4a; gr_user_id=74ff0df5-4caa-4dbc-9d56-7db3f6ee3f4a; b59341ccb802cc42_gr_last_sent_cs1=user_id:a97e9f72-0b86-4600-94f7-31db14296c9d; b59341ccb802cc42_gr_cs1=user_id:a97e9f72-0b86-4600-94f7-31db14296c9d; Hm_lvt_8ff3cd19706f65b6b82041b04c9270ba=1696000861; Hm_lvt_1e6ee218f5d7b8801b81556440a4e79f=1696000861; Hm_lvt_335dd6fa5d923b403ef636de92768dea=1696000861; b59341ccb802cc42_gr_last_sent_cs1=user_id:a97e9f72-0b86-4600-94f7-31db14296c9d; b59341ccb802cc42_gr_session_id=83c428ef-827f-4684-87ad-ea7eabc0d602; b59341ccb802cc42_gr_last_sent_sid_with_cs1=83c428ef-827f-4684-87ad-ea7eabc0d602; b59341ccb802cc42_gr_session_id_sent_vst=83c428ef-827f-4684-87ad-ea7eabc0d602; acw_tc=2760824916984271127327432eec737d80f7a8f8d22193a3d5da56468edbe8; Hm_lpvt_8ff3cd19706f65b6b82041b04c9270ba=1698427245; Hm_lpvt_1e6ee218f5d7b8801b81556440a4e79f=1698427245; b59341ccb802cc42_gr_cs1=user_id:a97e9f72-0b86-4600-94f7-31db14296c9d; Hm_lpvt_335dd6fa5d923b403ef636de92768dea=1698427246; SESSION=51d20781-ce17-4a15-9cf6-95ed81d7952b",
        "Origin-Domain" => "www.epubit.com",
    ];
    protected array $bookIds;
    protected string $bookPage;
    protected string $bookListUri = "https://www.epubit.com/pubcloud/content/front/portal/getUbookList?page=1&row=20";

    protected function configure()
    {
        // 指令配置
        $this->setName('Points')
            ->setDescription('get points from epubit.');
    }

    protected function execute(Input $input, Output $output)
    {
        $output->writeln('start get books process.');
//        $client = new Client();
//        $client->head();


        $output->writeln('Points');
    }

}
