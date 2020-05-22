<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/6
 * Time: 22:51
 */

namespace app\admin\controller;


class Workorder extends AdminBaseController
{

    public function index()
    {
        return $this->fetch('workorder');
    }

    /**
     * 获取工单列表
     */
    public function getorderworklist()
    {
        $model = model('workorder');
        $query = $model->getWorkOrderlist();
        if ($query) {
            ds_json_encode(10000, "获取工单列表成功", $query);
        } else {
            ds_json_encode(10001, "获取工单列表失败");
        }
    }

    /**
     * 结束工单
     */
    public function orderworkover()
    {
        $time = date('Y-m-d H:i:s', time());
        $model = model('workorder');
        $query = $model->overWorkOrder(input('param.wo_id'), $time);
        if ($query) {
            ds_json_encode(10000, "工单操作成功", $time);
        } else {
            ds_json_encode(10001, "工单操作失败");
        }
    }


    /**
     * 搜索工单
     */
    public function serachworkorderlist()
    {
        $condition = array();
        $workordertitle = input('param.workordertitle');
        $find = array('\\', '/', '%', '_', '&');
        $replace = array('\\\\', '\\/', '\%', '\_', '\&');
        $workordertitle = '%' . trim(str_replace($find, $replace, $workordertitle)) . '%';
        if ($workordertitle && trim($workordertitle) != "") {
            $condition['wo_title'] = ['like', $workordertitle];
        }

        $workorderstate = input('param.workorderstate');
        if ($workorderstate) {
            $condition["wo_state"] = $workorderstate;
        }

        $dateatart = input('param.dateatart');
        $dateend = input('param.dateend');

        if ($dateatart && $dateend) {
            $condition["wo_create_time"] = ['between', [$dateatart, $dateend]];
        } else {
            if ($dateatart) {
                $condition["wo_create_time"] = ['>=', $dateatart];
            }

            if ($dateend) {
                $condition["wo_create_time"] = ['<=', $dateend];
            }
        }

        $query = db('workorder')
            ->where($condition)
            ->order('wo_state desc')
            ->paginate(100);
        if ($query) {
            ds_json_encode(10000, "搜索工单成功", $query);
        } else {
            ds_json_encode(10001, "搜索工单失败");
        }
    }
}