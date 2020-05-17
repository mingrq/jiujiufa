<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/17
 * Time: 23:25
 */

namespace app\common\model;

use think\Model;

/**
 * 工单
 * @package app\common\model
 */
class WorkOrder extends Model
{

    /**
     * 获取工单列表
     * @param array $condition
     * @param string $field
     * @param int $page
     * @param string $order
     * @return mixed
     */
    public function getOrderlist($condition = array(), $field = '*', $page = 0, $order = 'wo_state desc'){
        if ($page) {
            $member_list = db('workorder')->field($field)->where($condition)->order($order)->paginate($page,false,['query' => request()->param()]);
            return $member_list;
        }
        else {
            return db('workorder')->field($field)->where($condition)->order($order)->select();
        }
    }

    /**
     * 处理订单
     * @param $workorder_id 工单id
     */
   public function disposeWorkOrder($workorder_id){
      return db('workorder')->where('wo_id',$workorder_id)->update(['wo_state'=> 1,'wo_dispose_time'=>time()]);
   }
}