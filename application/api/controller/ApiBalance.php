<?php
/**
 * 账户信息相关接口
 */

namespace app\api\controller;

use think\Exception;

class ApiBalance extends ApiBaseController
{
    private $username = ''; // 账号

    /**
     * 初始化参数和身份验证
     */
    public function _initialize()
    {
        if ($this->request->isPost()) {
            $username = trim($this->request->post("username"));
            $nonce_str = trim($this->request->post("nonce_str"));
            $sign = trim($this->request->post("sign"));
            if (!empty($username) && !empty($nonce_str) && !empty($sign)) {
                $this->username = $username;
                if (!parent::verify_ticket($username, $nonce_str, $sign)) {
                    ds_json_encode(10002, "身份验证不通过，账号或密码错误", null);
                }
            } else {
                ds_json_encode(10003, "请求参数格式错误", null);
            }
        } else {
            ds_json_encode(10003, "请求参数格式错误", null);
        }
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