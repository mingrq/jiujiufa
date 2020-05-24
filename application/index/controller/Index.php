<?php
/**
 * 首页
 */

namespace app\index\controller;

use app\common\controller\FrontendBase;

class Index extends FrontendBase
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 首页
     */
    public function index()
    {
        return $this->fetch();
    }
}
