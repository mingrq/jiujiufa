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
        $model = model('advertising');
        $query = $model->getaddverisingclass();
        $this->assign('data',$query);
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

    /**
     * 搜索广告
     */
    public function serachadverlist()
    {
        $condition = array();

        $serach_class = input('param.serach_class');
        if ($serach_class) {
            $condition["ad_class"] = $serach_class;
        }


        $dateatart = input('param.dateatart');
        $dateend = input('param.dateend');

        if ($dateatart && $dateend) {
            $condition["ad_add_time"] = ['between', [$dateatart, $dateend]];
        } else {
            if ($dateatart) {
                $condition["ad_add_time"] = ['>=', $dateatart];
            }

            if ($dateend) {
                $condition["ad_add_time"] = ['<=', $dateend];
            }
        }


        $query = db('v_advertising')
            ->where($condition)
            ->order('ad_id desc')
            ->paginate(100);
        if ($query) {
            ds_json_encode(10000, "搜索广告成功", $query);
        } else {
            ds_json_encode(10001, "搜索广告失败");
        }
    }

    /**
     * 删除广告
     */
    public function deladver(){
        $query=db('advertising')->where('ad_id',input('ad_id'))->delete();
        if ($query){
            ds_json_encode(10000, "删除广告成功");
        }
        ds_json_encode(10001, "删除广告失败");
    }

    /**
     * 添加广告
     */
    public function addadvertising(){
        $model = model('advertising');
        $query = $model->getaddverisingclass();
        $this->assign('data',$query);
        return $this->fetch();
    }

}