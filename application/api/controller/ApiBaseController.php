<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/30
 * Time: 22:59
 */

namespace app\api\controller;

use think\Controller;

class ApiBaseController extends Controller
{
    // 加密方法
    // 将username+password+sid进行拼接，对拼接后的新字符串进行32位的md5加密，并转化为小写后得到sign
    // 例如：username="lin",password="123456",sid="2016072601"
    // 先将password进行16位加密得到：md516=“49ba59abbe56e057”
    // 按顺序拼接成新的字符串 username+md516+sid后得到str= “lin49ba59abbe56e0572016072601”
    // 将str进行32位md5加密后转化为小写得到sign=“e9b1453566d04b86cbf5fc7afc89c7cb”

    // 验证加密签名
    public function verify_ticket($sid = '', $sign = '', $username = '')
    {
        $result = false;
        if (strlen($sid) >= 16 && strlen($sid) <= 40) {
            // 更具username查询用户
            $member = db("member")->where('member_login_name', '=', $username)->find();
            if (!empty($member) && !empty($member['member_id'])) {
                $sign_new = strtolower(md5($username . $member['member_login_pw'] . $sid));
                if ($sign_new == $sign) {
                    $result = true;
                }
            }
        }
        return $result;
    }
}