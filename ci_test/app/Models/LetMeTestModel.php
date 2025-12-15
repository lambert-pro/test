<?php


namespace App\Models;

class LetMeTestModel extends CIBaseModel {

    public function getList()
    {
        $sql  = "select * from let_me_test";
        return $this->db->query($sql)->getRow();
    }
}