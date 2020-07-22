<?php

namespace app\common\model;

use think\Model;

class BaseOrder extends Model
{
    /**
     * 获取底单列表
     */
    public function getbaseorder($condition = array(), $field = '*', $page = 100, $order = 'bo_create_time desc'){
        if ($page) {
            $member_list = db('v_base_order')->field($field)->where($condition)->order($order)->paginate($page, false, ['query' => request()->param()]);
            return $member_list;
        } else {
            return db('v_base_order')->field($field)->where($condition)->order($order)->select();
        }
    }

    /**
     * 处理申请
     * @param $workorder_id 工单id
     */
    public function overWorkOrder($baseorder_id,$time)
    {
        return db('base_order')->where('bo_id', $baseorder_id)->update(['bo_state' => 2, 'bo_over_time' =>$time]);
    }
    protected $pk = 'bo_id';
}