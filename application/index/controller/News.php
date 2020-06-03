<?php
/**
 * 文章
 */

namespace app\index\controller;

use app\common\controller\FrontendBase;
use app\common\model\Article;
use app\common\model\Notice;

class News extends FrontendBase
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 礼品资讯
     * ac_id = 1
     */
    public function lipin()
    {
        $article = new Article();
        $articleList = $article->where('ac_id', '=', 1)->paginate(10);
        $page = $articleList->render();

        $this->assign('page', $page);
        $this->assign('articleList', $articleList);
        return $this->fetch();
    }

    /**
     * 电商资讯
     * ac_id = 2
     */
    public function retailer()
    {
        $article = new Article();
        $articleList = $article->where('ac_id', '=', 2)->paginate(10);
        $page = $articleList->render();

        $this->assign('page', $page);
        $this->assign('articleList', $articleList);
        return $this->fetch();
    }

    /**
     * 常见问题
     * ac_id = 3
     */
    public function problem()
    {
        $article = new Article();
        $articleList = $article->where('ac_id', '=', 3)->paginate(10);
        $page = $articleList->render();

        $this->assign('page', $page);
        $this->assign('articleList', $articleList);
        return $this->fetch();
    }

    /**
     * 网站公告
     */
    public function notice()
    {
        $notice = new Notice();
        $noticeList = $notice->order('notice_create_time', 'desc')->paginate(10);
        $page = $noticeList->render();

        $this->assign('page', $page);
        $this->assign('noticeList', $noticeList);
        return $this->fetch();
    }

    /**
     * 资讯详情
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function detail()
    {
        $article_id = input('id');
        $articledetail = db('article')->where('article_id', $article_id)->find();
        $this->assign("articledetail", $articledetail);
        return $this->fetch();
    }

    /**
     * 公告详情
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function noticedetail()
    {
        $notice_id = input('id');
        $noticedetail = db('notice')->where('notice_id', $notice_id)->find();
        $this->assign("noticedetail", $noticedetail);
        return $this->fetch();
    }
}