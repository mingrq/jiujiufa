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
        if (!request()->isPost()) {
            return $this->fetch('addnotice');
        } else {
            $title = input("param.title");
            $description = input("param.description");
            $ueval = input("param.ueval");
            $query= db('notice')->insert(["notice_title"=>$title,"notice_description"=>$description,"notice_content"=>$ueval]);
            if ($query) {
                ds_json_encode(10000, "资讯添加成功");
            } else {
                ds_json_encode(10001, "资讯添加失败");
            }
        }
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

    /**
     * 删除公告
     */
    public function delnotice(){
        $query=db('notice')->where('notice_id',input('notice_id'))->delete();
        if ($query){
            ds_json_encode(10000, "删除公告成功");
        }
        ds_json_encode(10001, "删除公告失败");
    }

    /**
     * 搜索公告
     */
    public function serachnoticelist()
    {

        $condition = array();
        $serachtitle = input('param.serachtitle');
        $find = array('\\', '/', '%', '_', '&');
        $replace = array('\\\\', '\\/', '\%', '\_', '\&');
        $serachtitle = '%' . trim(str_replace($find, $replace, $serachtitle)) . '%';
        if ($serachtitle && trim($serachtitle) != "") {
            $condition['notice_title'] = ['like', $serachtitle];
        }

        $dateatart = input('param.dateatart');
        $dateend = input('param.dateend');

        if ($dateatart && $dateend) {
            $condition["notice_create_time"] = ['between', [$dateatart, $dateend]];
        } else {
            if ($dateatart) {
                $condition["notice_create_time"] = ['>=', $dateatart];
            }

            if ($dateend) {
                $condition["notice_create_time"] = ['<=', $dateend];
            }
        }


        $query = db('notice')
            ->where($condition)
            ->order('notice_id desc')
            ->paginate(100);
        if ($query) {
            ds_json_encode(10000, "搜索公告成功", $query);
        } else {
            ds_json_encode(10001, "搜索公告失败");
        }
    }
}