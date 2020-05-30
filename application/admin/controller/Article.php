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

    public function index()
    {
        $query = db('article_class')->select();
        $this->assign('artclass', $query);
        return $this->fetch('article_list');
    }

    /**
     * 添加文章
     * @return mixed
     */
    public function addarticle()
    {
        if (!request()->isPost()) {
            $query = db('article_class')->select();
            $this->assign('artclass', $query);
            return $this->fetch('article_add');
        } else {
            $title = input("param.title");
            $description = input("param.description");
            $adclass = input("param.adclass");
            $ueval = input("param.ueval");
            $file = request()->file('file');
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
            //如果不清楚文件上传的具体键名，可以直接打印$info来查看
            //获取文件（日期/文件名），$info->getSaveName()  **********不同之处，笔记笔记哦
            $ad_pic = $info->getSaveName();  //在测试的时候也可以直接打印文件名称来查看
            $ad_pic = DS . 'upload' . DS . $ad_pic;
            $query = db('article')->insert(["article_pic" => $ad_pic, "article_title" => $title, "article_description" => $description, "ac_id" => $adclass, "article_content" => $ueval]);
            if ($query) {
                ds_json_encode(10000, "资讯添加成功");
            } else {
                ds_json_encode(10001, "资讯添加失败");
            }
        }
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
    public function delarticle()
    {
        $query = db('article')->where('article_id', input('article_id'))->delete();
        if ($query) {
            ds_json_encode(10000, "删除资讯成功");
        }
        ds_json_encode(10001, "删除资讯失败");
    }

    /**
     * 搜索资讯
     */
    public function seracharticlelist()
    {
        $condition = array();
        $serachtitle = input('param.serachtitle');
        $find = array('\\', '/', '%', '_', '&');
        $replace = array('\\\\', '\\/', '\%', '\_', '\&');
        $serachtitle = '%' . trim(str_replace($find, $replace, $serachtitle)) . '%';
        if ($serachtitle && trim($serachtitle) != "") {
            $condition['article_title'] = ['like', $serachtitle];
        }

        $ac_id = input('param.serachclass');
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