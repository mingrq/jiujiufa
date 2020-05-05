<?php

namespace app\member\controller;

use app\common\controller\MemberBase;

class Account extends MemberBase
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 修改个人中心 个人信息
     */
    public function updatePersonInfo()
    {
        return $this->fetch();
    }
}