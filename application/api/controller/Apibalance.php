<?php
/**
 * 账户信息相关接口
 */

namespace app\api\controller;

use app\common\controller\ApiBase;

class Apibalance extends ApiBase
{
    private $username = ''; // 账号

    /**
     * 初始化参数和身份验证
     */
    protected function _initialize()
    {
        parent::_initialize();
        $this->username = trim($this->request->post("username"));
    }

    /**
     * 获取账户余额接口
     */
    public function getBalance()
    {
        $member = new Member();
        $memBalance = $member->field("member_balance")->where("member_login_name", "=", $this->username)->find();
        if (!empty($memBalance) && $memBalance['member_balance'] != '' && $memBalance['member_balance'] != null) {
            ds_json_encode(1, "账户余额获取成功", $memBalance['member_balance']);
        } else {
            ds_json_encode(10999, "未知系统错误", null);
        }
    }
}