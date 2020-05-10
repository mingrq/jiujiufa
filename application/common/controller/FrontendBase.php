<?php
/**
 * 前台控制器基类
 */

namespace app\common\controller;

use think\Controller;

class FrontendBase extends Controller
{
    protected function _initialize()
    {
    }

    /**
     * 空操作
     */
    public function _empty()
    {
    }
}