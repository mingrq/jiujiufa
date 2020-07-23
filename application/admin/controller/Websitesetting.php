<?php
/**
 * Created by PhpStorm.
 * User: 002
 * Date: 2020/6/3
 * Time: 15:33
 */

/**
 * 站点设置
 */

namespace app\admin\controller;

use app\common\model\Config;

class Websitesetting extends AdminBaseController
{

    public function index()
    {
        if (request()->isPost()) {
            $website_name = input('param.website_name');

            $website_logo = input('param.website_logo_url');
            $file = request()->file('website_logo');
            if ($file) {
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
                //如果不清楚文件上传的具体键名，可以直接打印$info来查看
                //获取文件（日期/文件名），$info->getSaveName()  **********不同之处，笔记笔记哦
                $ad_pic = $info->getSaveName();  //在测试的时候也可以直接打印文件名称来查看
                $website_logo = DS . 'upload' . DS . $ad_pic;
            }

            $website_top_logo = input('param.website_top_logo_url');
            $file = request()->file('website_top_logo');
            if ($file) {
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
                //如果不清楚文件上传的具体键名，可以直接打印$info来查看
                //获取文件（日期/文件名），$info->getSaveName()  **********不同之处，笔记笔记哦
                $ad_pic = $info->getSaveName();  //在测试的时候也可以直接打印文件名称来查看
                $website_top_logo = DS . 'upload' . DS . $ad_pic;
            }

            $website_keyword = input('param.website_keyword');
            $website_icp = input('param.website_icp');
            $website_copyright = input('param.website_copyright');
            $website_statistics = input('param.website_statistics');
            $website_home_title = input('param.website_home_title');
            $icp_link= input('param.icp_link');
            $websiteinfo = new Config();
            $list = [
                ['config_id' => 15, 'config_value' => $website_name],
                ['config_id' => 16, 'config_value' => $website_logo],
                ['config_id' => 17, 'config_value' => $website_keyword],
                ['config_id' => 18, 'config_value' => $website_icp],
                ['config_id' => 19, 'config_value' => $website_copyright],
                ['config_id' => 20, 'config_value' => $website_statistics],
                ['config_id' => 21, 'config_value' => $website_top_logo],
                ['config_id' => 24, 'config_value' => $website_home_title],
                ['config_id' => 32, 'config_value' => $icp_link]
            ];
            $query = $websiteinfo->saveAll($list);
            if ($query) {
                ds_json_encode(10000, "站点信息设置成功");
            } else {
                ds_json_encode(10001, "站点信息设置失败");
            }

        } else {
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
                if ($info['config_code'] == 'website_home_title') {
                    $this->assign('website_home_title', $info['config_value']);
                }
                if ($info['config_code'] == 'icp_link') {
                    $this->assign('icp_link', $info['config_value']);
                }
            }
            return $this->fetch('websitesetting');
        }
    }
}