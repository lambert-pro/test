<?php


namespace app\model;


class LetMeTest extends TPBaseModel
{
    public function getList()
    {
        return LetMeTest::find(1)->toArray();
    }

}