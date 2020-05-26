<?php
/**
 * 文章管理
 * User: ming
 * Date: 2020/5/7
 * Time: 20:16
 */

namespace app\admin\controller;


class Article extends AdminBaseController
{

    public function index(){
        return $this->fetch('article_list');
    }

    /**
     * 添加文章
     * @return mixed
     */
    public function addarticle(){
        return $this->fetch('article_add');
    }

    /**
     * 获取文章列表
     */
    public function articlelist()
    {
        $article_mod = model('article');
        $articleList = $article_mod->articlelist();
        if ($articleList) {
            ds_json_encode(10000, "获取会员列表成功", $articleList);
        } else {
            ds_json_encode(10001, "获取会员列表失败");
        }
    }

    /**
     * 删除文章
     */
    public function delarticle(){
        $query=db('article')->where('article_id',input('article_id'))->delete();
        if ($query){
            ds_json_encode(10000, "删除资讯成功");
        }
        ds_json_encode(10001, "删除资讯失败");
    }
}