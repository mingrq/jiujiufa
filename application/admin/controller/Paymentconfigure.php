<?php
/**
 *
 * 支付配置
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/7
 * Time: 20:33
 */

namespace app\admin\controller;


class Paymentconfigure extends AdminBaseController
{

    public function index()
    {
        $alipay_app_id = db('config')->where('config_id', '1')->value('config_value');
        $alipay_merchant_private_key = db('config')->where('config_id', '2')->value('config_value');
        $alipay_public_key = db('config')->where('config_id', '3')->value('config_value');
        $this->assign('alipay_app_id', $alipay_app_id);
        $this->assign('alipay_merchant_private_key', $alipay_merchant_private_key);
        $this->assign('alipay_public_key', $alipay_public_key);
        return $this->fetch('payment_method');
    }

    /**
     * 添加支付宝配置
     */
    public function setalipayconfigure()
    {
        $alipay_app_id = input('param.alipay_app_id');
        $alipay_merchant_private_key = input('param.alipay_merchant_private_key');
        $alipay_public_key = input('param.alipay_public_key');
        db('config')->where('config_code', 'alipay_app_id')->setField('config_value', $alipay_app_id);
        db('config')->where('config_code', 'alipay_merchant_private_key')->setField('config_value', $alipay_merchant_private_key);
        db('config')->where('config_code', 'alipay_public_key')->setField('config_value', $alipay_public_key);
        ds_json_encode(10000,"支付宝支付配置成功");
    }

}