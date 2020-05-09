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
     * 个人信息
     */
    public function personInfo()
    {
        return $this->fetch();
    }

    /**
     * 修改个人中心 个人信息
     */
    public function updatePersonInfo()
    {
        return $this->fetch();
    }

    /**
     * 邀请链接
     */
    public function mySpreadLine()
    {
        return $this->fetch();
    }

    /**
     * 升级代理
     */
    public function upMyVip()
    {
        return $this->fetch();
    }
}