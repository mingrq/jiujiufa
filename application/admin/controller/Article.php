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
       $query= db('advertising_class')->select();
       $this->assign('artclass',$query);
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
            ds_json_encode(10000, "获取资讯列表成功", $articleList);
        } else {
            ds_json_encode(10001, "获取资讯列表失败");
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

    /**
     * 搜索礼品
     */
    public function seracharticlelist()
    {
        $ac_id= input('param.serachclass');
        if ($ac_id) {
            $condition["ac_id"] = $ac_id;
        }

        $dateatart = input('param.dateatart');
        $dateend = input('param.dateend');

        if ($dateatart && $dateend) {
            $condition["article_time"] = ['between', [$dateatart, $dateend]];
        } else {
            if ($dateatart) {
                $condition["article_time"] = ['>=', $dateatart];
            }

            if ($dateend) {
                $condition["article_time"] = ['<=', $dateend];
            }
        }


        $query = db('v_article')
            ->where($condition)
            ->order('article_id desc')
            ->paginate(100);
        if ($query) {
            ds_json_encode(10000, "搜索资讯成功", $query);
        } else {
            ds_json_encode(10001, "搜索资讯失败");
        }
    }
}