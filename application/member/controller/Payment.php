<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/22
 * Time: 23:52
 */

/**
 * 支付回调
 */
namespace app\member\controller;
use think\Controller;
use malipay\malipay;
class Payment extends Controller
{
    /**
     * 异步通知地址
     */
    public function alipaynotify(){

    }

    /**
     * 跳转页面
     */
    public function alipayreturn(){

        return $this->fetch();
    }
}