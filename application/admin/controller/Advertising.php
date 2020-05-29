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
        $this->assign('data', $query);
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
    public function getaddverisingclass()
    {
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
    public function deladver()
    {
        $query = db('advertising')->where('ad_id', input('ad_id'))->delete();
        if ($query) {
            ds_json_encode(10000, "删除广告成功");
        }
        ds_json_encode(10001, "删除广告失败");
    }

    /**
     * 添加广告
     */
    public function addadvertising()
    {
        if (!request()->isPost()) {
            $model = model('advertising');
            $query = $model->getaddverisingclass();
            $this->assign('data', $query);
            return $this->fetch();
        } else {
            $ad_class = input('param.set_adv_class');
            $ad_link = input('param.adv_link');
            //$ad_pic = input('param.adv_pic_sel');
            $file = request()->file('file');
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
            //如果不清楚文件上传的具体键名，可以直接打印$info来查看
            -            //获取文件（日期/文件名），$info->getSaveName()  **********不同之处，笔记笔记哦
            $ad_pic = $info->getSaveName();  //在测试的时候也可以直接打印文件名称来查看
            $ad_pic = DS . 'upload' . DS . $ad_pic;
            if ($ad_pic) {
                $query = db('advertising')->insert(['ad_class' => $ad_class, 'ad_pic' => $ad_pic, 'ad_link' => $ad_link]);
                if ($query) {
                    ds_json_encode(10000, "添加广告成功", $info);
                }
            }
            ds_json_encode(10001, "添加广告失败");
        }
    }


}