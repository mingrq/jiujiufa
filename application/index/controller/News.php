<?php
/**
 * 文章
 */

namespace app\index\controller;

use app\common\controller\FrontendBase;

class News extends FrontendBase
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 礼品资讯
     */
    public function lipin()
    {
        return $this->fetch();
    }

    public function detail()
    {
        return $this->fetch();
    }
}