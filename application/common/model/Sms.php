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
     * 发送短信验证码-----------发送函数，剪切到需要发送短信的地方，需要改
     */
    public function sendver()
    {
        if (!request()->isPost()) {
            ds_json_encode(10001, '请求格式不正确');
        } else {
            $mobile = input('param.mobile');
            if (Cache::get($mobile)) {
                ds_json_encode(10001, '验证码发送太频繁');
            } else {
                $mode = input('param.mode');
                $code = rand(1000, 9999);
                Cache::set($mobile, $code, 58);
                $result = alisendSms($mobile, $code, $mode);
                if ($result->Code == 'OK') {
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
    public function alisendSms($phone,$code,$template){


        $keyid = 'LTAI4FiVu39DQa3riodQNeMu';
        $keyserver = '7tqnVUWM6grOQEY4IEgh4DV4rAZgKv';
        $sign = '邦管家';

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