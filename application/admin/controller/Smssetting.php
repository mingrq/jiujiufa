<?php
/**
 * Created by PhpStorm.
 * User: 002
 * Date: 2020/5/23
 * Time: 18:36
 */

namespace app\admin\controller;


class Smssetting extends AdminBaseController
{

    public function index()
    {

        $ali_sms_keyid = db('config')->where('config_id', '9')->value('config_value');
        $ali_sms_accessKeySecret = db('config')->where('config_id', '10')->value('config_value');
        $ali_sms_sign = db('config')->where('config_id', '11')->value('config_value');
        $ali_sms_temp_regiter = db('config')->where('config_id', '12')->value('config_value');
        $ali_sms_temp_login = db('config')->where('config_id', '13')->value('config_value');
        $ali_sms_temp_pwback = db('config')->where('config_id', '14')->value('config_value');
        $this->assign('ali_sms_keyid', $ali_sms_keyid);
        $this->assign('ali_sms_accessKeySecret', $ali_sms_accessKeySecret);
        $this->assign('ali_sms_sign', $ali_sms_sign);
        $this->assign('ali_sms_temp_regiter', $ali_sms_temp_regiter);
        $this->assign('ali_sms_temp_login', $ali_sms_temp_login);
        $this->assign('ali_sms_temp_pwback', $ali_sms_temp_pwback);
        return $this->fetch('smssetting');

        //ds_json_encode(10000,"adsfas",$query);
    }


}