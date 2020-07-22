<?php

namespace app\api\controller;

use app\common\model\Member;

class ApiOrder extends ApiBaseController
{
    private $sid = '';
    private $sign = '';
    private $username = '';

    public function _initialize()
    {
        /**
         * 参数和身份验证
         */
        $param = $this->request->post();
        if (!empty($param) && !empty($param['sid']) && !empty($param['sign']) && !empty($param['username'])) {
            $this->sid = $param['sid'];
            $this->sign = $param['sign'];
            $this->username = $param['username'];

            if (!parent::verify_ticket($this->sid, $this->sign, $this->username)) {
                ds_json_encode(10002, "身份验证不通过，账号或密码错误", null);
            }
        } else {
            ds_json_encode(10003, "请求参数错误", null);
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
        /*$param = $this->request->post();
        if (!empty($param) && !empty($param['sid']) && !empty($param['sign']) && !empty($param['username'])) {
            $sid = $param['sid'];
            $sign = $param['sign'];
            $username = $param['username'];
            if (parent::verify_ticket($sid, $sign, $username)) {
                $member = new Member();
                $memBalance = $member->where("member_login_name", "=", $username)->field("member_balance")->find();
                if (!empty($memBalance) && $memBalance['member_balance'] != '' && $memBalance['member_balance'] != null) {
                    ds_json_encode(1, "账户余额获取成功", $memBalance['member_balance']);
                } else {
                    ds_json_encode(10999, "未知系统错误", null);
                }
            } else {
                ds_json_encode(10002, "身份验证不通过，账号或密码错误", null);
            }
        } else {
            ds_json_encode(10003, "请求参数错误", null);
        }*/
    }

    /**
     * 获取商品信息接口
     */
    public function goodsList()
    {

    }

    /**
     *  面单号 下单的接口
     */
    public function orderGift()
    {

    }
}