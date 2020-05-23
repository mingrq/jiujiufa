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
        if (request()->isPost()){

        }else{
            $query = db('config')->where('config_id', 'in', [9, 10, 11, 12, 13, 14])->select();
            $this->assign('smsconfig',$query);
            return $this->fetch('smssetting');
        }

        //ds_json_encode(10000,"adsfas",$query);
    }



}