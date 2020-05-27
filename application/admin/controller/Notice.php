<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/7
 * Time: 20:20
 */

namespace app\admin\controller;


class Notice extends AdminBaseController
{

    public function index(){
        return $this->fetch('notice');
    }

    /**
     * 添加公告
     * @return mixed
     */
    public function addnotice(){
        return $this->fetch('addnotice');
    }


    /**
     * 获取公告列表
     */
    public function noticelist()
    {
        $notice_mod = model('notice');
        $noticeList = $notice_mod->noticelist();
        if ($noticeList) {
            ds_json_encode(10000, "获取公告列表成功", $noticeList);
        } else {
            ds_json_encode(10001, "获取公告列表失败");
        }
    }


}