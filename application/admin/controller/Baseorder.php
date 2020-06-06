<?php
/**
 * Created by PhpStorm.
 * User: 002
 * Date: 2020/6/6
 * Time: 9:24
 */

namespace app\admin\controller;


use app\common\model\Config;

class Baseorder extends AdminBaseController
{

    public function index()
    {
        if (request()->isPost()) {
            $model = model('baseorder');
            $query = $model->getbaseorder();
            if ($query) {
                ds_json_encode(10000, "获取底单列表成功", $query);
            } else {
                ds_json_encode(10001, "获取底单列表失败");
            }
        } else {
            return $this->fetch('baseorder');
        }
    }


    /**
     * 处理申请
     */
    public function baseorderover()
    {
        $time = date('Y-m-d H:i:s', time());
        $model = model('baseorder');
        $query = $model->overWorkOrder(input('param.bo_id'), $time);
        if ($query) {
            ds_json_encode(10000, "申请操作成功", $time);
        } else {
            ds_json_encode(10001, "申请操作失败");
        }
    }

    /**
     * 售后配置
     * @return mixed
     */
    public function aftersaleconfigure()
    {
        if (request()->isPost()) {
            $refund_file_pic = input('param.refund_file_url');
            $refund_file = request()->file('refund_file');


            if ($refund_file) {
                // 移动到框架应用根目录/public/uploads/ 目录下
                $refund_file_info = $refund_file->move(ROOT_PATH . 'public' . DS . 'files' . DS . 'upload');
                //如果不清楚文件上传的具体键名，可以直接打印$info来查看
                //获取文件（日期/文件名），$info->getSaveName()  **********不同之处，笔记笔记哦
                $refund_file_name = $refund_file_info->getSaveName();  //在测试的时候也可以直接打印文件名称来查看
                $refund_file_pic = DS . 'files' . DS . 'upload' . DS . $refund_file_name;
            }

            $restraint_file_pic = input('param.restraint_file_url');
            $restraint_file = request()->file('restraint_file');
            if ($restraint_file) {
                // 移动到框架应用根目录/public/uploads/ 目录下
                $restraint_file_info = $restraint_file->move(ROOT_PATH . 'public' .DS . 'files' . DS . 'upload');
                //如果不清楚文件上传的具体键名，可以直接打印$info来查看
                //获取文件（日期/文件名），$info->getSaveName()  **********不同之处，笔记笔记哦
                $restraint_file_name = $restraint_file_info->getSaveName();  //在测试的时候也可以直接打印文件名称来查看
                $restraint_file_pic = DS . 'files' .DS . 'upload' . DS . $restraint_file_name;
            }


            $aftersaleconfig = new Config();
            $list = [
                ['config_id' => 22, 'config_value' => $refund_file_pic],
                ['config_id' => 23, 'config_value' => $restraint_file_pic]
            ];
            $query = $aftersaleconfig->saveAll($list);
            if ($query) {
                ds_json_encode(10000, "售后配置设置成功");
            } else {
                ds_json_encode(10001, "售后配置设置失败");
            }
        } else {
            $query = db('config')->where('config_id', 'in', [22, 23])->select();
            foreach ($query as $info) {
                if ($info['config_code'] == 'refund_file') {
                    $this->assign('refund_file', $info['config_value']);
                }
                if ($info['config_code'] == 'restraint_file') {
                    $this->assign('restraint_file', $info['config_value']);
                }
            }
            return $this->fetch();
        }
    }



    /**
     * 搜索底单
     */
    public function serachbaseorderover()
    {
        $condition = array();
        $serachparam = input('param.serachparam');
        $find = array('\\', '/', '%', '_', '&');
        $replace = array('\\\\', '\\/', '\%', '\_', '\&');
        $serachparam = '%' . trim(str_replace($find, $replace, $serachparam)) . '%';
        if ($serachparam && trim($serachparam) != "") {
            $condition['member_mobile|member_qq|bo_email'] = ['like', $serachparam];
        }

        $orderstate = input('param.orderstate');
        if ($orderstate) {
            $condition["bo_state"] = $orderstate;
        }

        $dateatart = input('param.dateatart');
        $dateend = input('param.dateend');

        if ($dateatart && $dateend) {
            $condition["bo_create_time"] = ['between', [$dateatart, $dateend]];
        } else {
            if ($dateatart) {
                $condition["bo_create_time"] = ['>=', $dateatart];
            }

            if ($dateend) {
                $condition["bo_create_time"] = ['<=', $dateend];
            }
        }

        $query = db('v_base_order')
            ->where($condition)
            ->order('bo_state asc')
            ->paginate(100);
        if ($query) {
            ds_json_encode(10000, "搜索底单成功", $query);
        } else {
            ds_json_encode(10001, "搜索底单失败");
        }
    }

}