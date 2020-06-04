<?php
/**
 * Created by PhpStorm.
 * User: 002
 * Date: 2020/6/4
 * Time: 17:39
 */

namespace app\member\controller;


use app\common\controller\MemberBase;

class Aftersale extends MemberBase
{

    /**
     * 申请底单
     */
    public function didan()
    {
        if (request()->isPost()) {
            $memberid = session("MUserId");
            $field = ['order_no', 'tracking_number', 'consignee_name', 'shipping_address', 'express_name', 'create_time', 'order_pay', 'order_state', 'goodsTitle', 'consignee_phone'];
            $query = db('order')->field($field)->where('order_state', 'in', [2, 3])->where('member_id', $memberid)->select();
            if ($query) {
                ds_json_encode(10000, "获取底单成功", $query);
            } else {
                ds_json_encode(10001, "获取底单失败");
            }

        } else {
            return $this->fetch();
        }
    }

    /**
     * 售后工单
     */
    public function workorder()
    {
        if (request()->isPost()) {
            $model = model('workorder');
            $condition = array();
            $condition['member_id'] = session("MUserId");
            $query = $model->getWorkOrderlist($condition);
            if ($query) {
                ds_json_encode(10000, "获取工单列表成功", $query);
            } else {
                ds_json_encode(10001, "获取工单列表失败");
            }
        } else {
            return $this->fetch();
        }
    }

    /**
     * 搜索工单
     */
    public function serachworkorderlist()
    {
        $condition = array();
        $condition['member_id'] = session("MUserId");
        $workordertitle = input('param.workordertitle');
        $find = array('\\', '/', '%', '_', '&');
        $replace = array('\\\\', '\\/', '\%', '\_', '\&');
        $workordertitle = '%' . trim(str_replace($find, $replace, $workordertitle)) . '%';
        if ($workordertitle && trim($workordertitle) != "") {
            $condition['wo_title'] = ['like', $workordertitle];
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
            ->order('wo_state asc')
            ->paginate(100);
        if ($query) {
            ds_json_encode(10000, "搜索工单成功", $query);
        } else {
            ds_json_encode(10001, "搜索工单失败");
        }
    }

    /**
     * 写工单
     * @return mixed
     */
    public function addworkorder()
    {
        if (request()->isPost()) {
            $workordertit = input('param.workordertit');
            $wolinkman = input('param.wolinkman');
            $wophone = input('param.wophone');
            $workordercontent = input('param.workordercontent');
            $condition = array();
            $condition['wo_title']=$workordertit;
            $condition['wo_linkman']=$wolinkman;
            $condition['wo_phone']=$wophone;
            $condition['wo_content']=$workordercontent;
            $condition['member_id']=session("MUserId");
           $query= db('workorder')->insert($condition);
           if ($query){
               ds_json_encode(10000, "工单提交成功");
           }else{
               ds_json_encode(10001, "工单提交失败");
           }

        } else {
            return $this->fetch();
        }
    }
}