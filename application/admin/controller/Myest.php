<?php
/**
 * Created by PhpStorm.
 * User: 002
 * Date: 2020/5/21
 * Time: 11:10
 */

namespace app\admin\controller;
use malipay\malipay;

class Myest extends AdminBaseController
{

    /**
     * 批量添加用户
     */
    public function batchadduser()
    {
        for ($i = 0; $i < 100; $i++) {
            if ($i<10){
                $mm = $i."2";
            }else{
                $mm = $i;
            }
            $data =["member_login_name"=>"133212".$mm."354","member_login_pw"=>"96e79218965eb72c92a549dd5a330112","member_mobile"=>"133212".$mm."354","member_qq"=>"123456".$i."478","member_rank"=>"1"];
            $mod = model('register');
            $mod->register_member($data);
        }
        ds_json_encode(10000,"");
    }


    /**
     * 支付测试
     */
    public function paytest(){
            $pay = new malipay();

    }
}