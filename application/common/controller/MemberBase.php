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
        $mode = model('websiteinfo');
        $query = $mode->getWebsiteInfo();
        foreach ($query as $info) {
            if ($info['config_code'] == 'website_name') {
                $this->assign('website_name', $info['config_value']);
            }
            if ($info['config_code'] == 'website_logo') {
                $this->assign('website_logo', $info['config_value']);
            }
            if ($info['config_code'] == 'website_keyword') {
                $this->assign('website_keyword', $info['config_value']);
            }
            if ($info['config_code'] == 'website_icp') {
                $this->assign('website_icp', $info['config_value']);
            }
            if ($info['config_code'] == 'website_copyright') {
                $this->assign('website_copyright', $info['config_value']);
            }
            if ($info['config_code'] == 'website_statistics') {
                $this->assign('website_statistics', $info['config_value']);
            }
            if ($info['config_code'] == 'website_top_logo') {
                $this->assign('website_top_logo', $info['config_value']);
            }
        }
        $MUserId = session('MUserId');
        $MUserName = session('MUserName');
        if (empty($MUserId) || empty($MUserName)) {
//            $this->error("请先登录", url("member/login/login"));
            $this->redirect(url("member/login/login"));
        }
    }

    /**
     * 空操作
     */
    public function _empty()
    {
    }
}