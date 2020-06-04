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
            $field = ['order_no', 'tracking_number','consignee_name','shipping_address','express_name','create_time','order_pay','order_state','goodsTitle','consignee_phone'];
            $query = db('order')->field($field)->where('order_state', 'in', [2, 3])->where('member_id', $memberid)->select();
            if ($query){
                ds_json_encode(10000, "获取底单成功", $query);
            }else{
                ds_json_encode(10001, "获取底单失败");
            }

        } else {
            return $this->fetch();
        }
    }

    /**
     * 售后工单
     */
    public function workorder(){
        if (request()->isPost()) {

        } else {
            return $this->fetch();
        }
    }
}