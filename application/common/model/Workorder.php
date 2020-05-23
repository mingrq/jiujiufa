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
class Workorder extends Model
{

    /**
     * 获取工单列表
     * @param array $condition
     * @param string $field
     * @param int $page
     * @param string $order
     * @return mixed
     */
    public function getWorkOrderlist($condition = array(), $field = '*', $page = 100, $order = 'wo_state asc')
    {
        return db('workorder')->field($field)->where($condition)->order($order)->paginate($page, false, ['query' => request()->param()]);
    }

    /**
     * 处理订单
     * @param $workorder_id 工单id
     */
    public function overWorkOrder($workorder_id,$time)
    {
        return db('workorder')->where('wo_id', $workorder_id)->update(['wo_state' => 2, 'wo_dispose_time' =>$time]);
    }
}