<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/7
 * Time: 20:33
 */

namespace app\admin\controller;


class Paymentconfigure extends AdminBaseController
{

    public function index(){
        return $this->fetch('payment_method');

    }
}