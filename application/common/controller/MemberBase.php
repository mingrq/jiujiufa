<?php
/**
 * 会员控制器基类
 */

namespace app\common\controller;

use think\Controller;

class MemberBase extends Controller
{
    /**
     *
     */
    protected function _initialize()
    {
        $MUserId = session('MUserId');
        $MUserName = session('MUserName');
        if (empty($MUserId) || empty($MUserName)){
            $this->error("请先登录", url("member/login/login"));
        }
    }

    /**
     * 空操作
     */
    public function _empty()
    {
    }
}