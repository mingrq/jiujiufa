<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/6/10
 * Time: 23:08
 */

namespace app\admin\controller;


use app\common\model\Config;

class Service extends AdminBaseController
{

    public function index(){
        if (request()->isPost()) {
            $saler_wechat_group_file_pic = input('param.saler_wechat_group_url');
            $saler_wechat_group_file = request()->file('saler_wechat_group');


            if ($saler_wechat_group_file) {
                // 移动到框架应用根目录/public/uploads/ 目录下
                $saler_wechat_group_file_info = $saler_wechat_group_file->move(ROOT_PATH . 'public' . DS . 'files' . DS . 'upload');
                //如果不清楚文件上传的具体键名，可以直接打印$info来查看
                //获取文件（日期/文件名），$info->getSaveName()  **********不同之处，笔记笔记哦
                $saler_wechat_group_file_name = $saler_wechat_group_file_info->getSaveName();  //在测试的时候也可以直接打印文件名称来查看
                $saler_wechat_group_file_pic = DS . 'files' . DS . 'upload' . DS . $saler_wechat_group_file_name;
            }

            $saler_wechat_file_pic = input('param.saler_wechat_url');
            $saler_wechat_file = request()->file('saler_wechat');
            if ($saler_wechat_file) {
                // 移动到框架应用根目录/public/uploads/ 目录下
                $saler_wechat_file_info = $saler_wechat_file->move(ROOT_PATH . 'public' .DS . 'files' . DS . 'upload');
                //如果不清楚文件上传的具体键名，可以直接打印$info来查看
                //获取文件（日期/文件名），$info->getSaveName()  **********不同之处，笔记笔记哦
                $saler_wechat_file_name = $saler_wechat_file_info->getSaveName();  //在测试的时候也可以直接打印文件名称来查看
                $saler_wechat_file_pic = DS . 'files' .DS . 'upload' . DS . $saler_wechat_file_name;
            }

            $saler_time = input('param.saler_time');
            $saler_mobile= input('param.saler_mobile');
            $saler_qq= input('param.saler_qq');
            $aftersaleconfig = new Config();
            $list = [
                ['config_id' => 31, 'config_value' => $saler_time],
                ['config_id' => 30, 'config_value' => $saler_mobile],
                ['config_id' => 29, 'config_value' => $saler_wechat_group_file_pic],
                ['config_id' => 28, 'config_value' => $saler_wechat_file_pic],
                ['config_id' => 27, 'config_value' => $saler_qq]

            ];
            $query = $aftersaleconfig->saveAll($list);
            if ($query) {
                ds_json_encode(10000, "客服配置设置成功");
            } else {
                ds_json_encode(10001, "客服配置设置失败");
            }
        }else{
            $query = db('config')->where('config_id', 'in', [27, 28,29,30,31])->select();
            foreach ($query as $info) {
                if ($info['config_code'] == 'saler_qq') {
                    $this->assign('saler_qq', $info['config_value']);
                }
                if ($info['config_code'] == 'saler_wechat') {
                    $this->assign('saler_wechat', $info['config_value']);
                }
                if ($info['config_code'] == 'saler_wechat_group') {
                    $this->assign('saler_wechat_group', $info['config_value']);
                }
                if ($info['config_code'] == 'saler_mobile') {
                    $this->assign('saler_mobile', $info['config_value']);
                }
                if ($info['config_code'] == 'saler_time') {
                    $this->assign('saler_time', $info['config_value']);
                }
            }
            return $this->fetch('service');
        }

    }
}