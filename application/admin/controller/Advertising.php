<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/7
 * Time: 21:48
 */

namespace app\admin\controller;


class Advertising extends AdminBaseController
{
    public function index()
    {
        return $this->fetch('advertising');
    }

    /**
     * 获取广告列表
     */
    public function getadvertislist()
    {
        $model = model('advertising');
        $query = $model->getadvertislist();
        if ($query) {
            ds_json_encode(10000, "获取广告列表成功", $query);
        } else {
            ds_json_encode(10000, "获取广告列表失败");
        }
    }

    /**
     * 获取广告分类
     */
    public function getaddverisingclass(){
        $model = model('advertising');
        $query = $model->getaddverisingclass();
        if ($query) {
            ds_json_encode(10000, "获取广告分类成功", $query);
        } else {
            ds_json_encode(10000, "获取广告分类失败");
        }
    }
}