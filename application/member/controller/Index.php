<?php

namespace app\member\controller;

use app\common\controller\MemberBase;

class Index extends MemberBase {
    public function _initialize() {
        parent::_initialize();
    }

    public function index(){
        return $this->fetch();
    }
}