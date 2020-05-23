<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/22
 * Time: 23:47
 */

namespace app\common\model;

use think\Model;

/**
 * 短信
 * Class Sms
 * @package app\common\model
 */
class Sms extends Model
{

    /**
     * 发送短信验证码
     * @param $mobile
     * @param $mode  SMSLOGIN | REGISTER | FINDBACK
     */
    const SMSLOGIN = 'login';//登录
    const REGISTER = 'register';//注册
    const FINDBACK = 'findback';//找回密码

    public function sendverify($mobile, $mode)
    {
        if (!request()->isPost()) {
            ds_json_encode(10001, '请求格式不正确');
        } else {
            $templete = "";
            if ($mode == 'login') {
                $templete = rkcache('SMSLOGIN');
                if (!$templete) {
                    $templete = db('config')->where('config_code', 'ali_sms_temp_login')->value('config_value');
                    wkcache('SMSLOGIN', $templete);
                }
            } else if ($mode == 'register') {
                $templete = rkcache('REGISTER');
                if (!$templete) {
                    $templete = db('config')->where('config_code', 'ali_sms_temp_regiter')->value('config_value');
                    wkcache('REGISTER', $templete);
                }
            } else if ($mode == 'findback') {
                $templete = rkcache('FINDBACK');
                if (!$templete) {
                    $templete = db('config')->where('config_code', 'ali_sms_temp_pwback')->value('config_value');
                    wkcache('FINDBACK', $templete);
                }
            }
            if (rkcache($mobile)) {
                ds_json_encode(10001, '验证码发送太频繁');
            } else {
                $code = rand(1000, 9999);
                $result = $this->alisendSms($mobile, $code, $templete);
                if ($result->Code == 'OK') {
                    wkcache($mobile, $code, 58);
                    ds_json_encode(10000, '验证码发送成功');
                } else {
                    ds_json_encode(10001, '验证码发送失败');
                }
            }
        }
    }



    /**
     *
     * 阿里发送短信
     * @param $phone
     * @param $code
     * @param $template
     * @return mixed|\SimpleXMLElement
     */
    public function alisendSms($phone, $code, $template)
    {
        $keyid = rkcache('smskeyid');
        if (!$keyid) {
            $keyid = db('config')->where('config_code', 'ali_sms_keyid')->value('config_value');
            wkcache('smskeyid', $keyid);
        }
        $keyserver = rkcache('smsaccessKeySecret');
        if (!$keyserver) {
            $keyserver = db('config')->where('config_code', 'ali_sms_accessKeySecret')->value('config_value');
            wkcache('smsaccessKeySecret', $keyserver);
        }
        $sign = rkcache('smssign');
        if (!$sign) {
            $sign = db('config')->where('config_code', 'ali_sms_sign')->value('config_value');
            wkcache('smssign', $sign);
        }

        //引进阿里的配置文件
        Vendor('api_sdk.vendor.autoload');

        // TP5.1及以上用require_once

        // 加载区域结点配置
        \Aliyun\Core\Config::load();
        $profile = \Aliyun\Core\Profile\DefaultProfile::getProfile('cn-hangzhou', $keyid, $keyserver);
        // 增加服务结点
        \Aliyun\Core\Profile\DefaultProfile::addEndpoint('cn-hangzhou', 'cn-hangzhou', 'Dysmsapi', 'dysmsapi.aliyuncs.com');
        // 初始化AcsClient用于发起请求
        $acsClient = new \Aliyun\Core\DefaultAcsClient($profile);
        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new \Aliyun\Api\Sms\Request\V20170525\SendSmsRequest();
        // 必填，设置雉短信接收号码
        $request->setPhoneNumbers($phone);
        // 必填，设置签名名称
        $request->setSignName($sign);
        // 必填，设置模板CODE
        $request->setTemplateCode($template);
        $params = array(
            'code' => $code,
        );
        // 可选，设置模板参数
        $request->setTemplateParam(json_encode($params));

        // 发起访问请求
        $acsResponse = $acsClient->getAcsResponse($request);
        // 打印请求结果

        return $acsResponse;

    }

}