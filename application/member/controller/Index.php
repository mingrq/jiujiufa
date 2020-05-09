<?php

namespace app\member\controller;

use app\common\controller\MemberBase;

class Index extends MemberBase
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 会员中心 首页
     */
    public function index()
    {
        return $this->fetch();
    }
}