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
    /**
     * 验证加密签名
     * @param string $username 账号
     * @param string $nonce_str 随机字符串，长度要求在32位以内
     * @param string $sign 签名  MD532签名username+pwd+nonce_str转化为小写
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
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