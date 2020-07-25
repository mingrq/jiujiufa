<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/30
 * Time: 22:59
 */

namespace app\common\controller;

use think\Controller;

class ApiBase extends Controller
{
    protected function _initialize()
    {
        if ($this->request->isPost()) {
            $username = trim($this->request->post("username"));
            $nonce_str = trim($this->request->post("nonce_str"));
            $sign = trim($this->request->post("sign"));
            if (!empty($username) && !empty($nonce_str) && !empty($sign)) {
                if (!self::verify_ticket($username, $nonce_str, $sign)) {
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
     * 验证加密签名
     * username 账号
     * nonce_str 随机字符串，长度要求在32位以内
     * sign 签名  MD532签名username+pwd+nonce_str转化为小写
     */
    public function verify_ticket($username = '', $nonce_str = '', $sign = '')
    {
        $result = false;
        // 根据username查询用户
        $member = db("member")->where('member_login_name', '=', $username)->find();
        if (!empty($member) && !empty($member['member_id'])) {
            $sign_new = strtolower(md5($username . $member['member_login_pw'] . $nonce_str));
            if ($sign_new == $sign) {
                $result = true;
            }
        }
        return $result;
    }
}