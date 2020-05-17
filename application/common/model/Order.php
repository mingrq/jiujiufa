<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/17
 * Time: 23:21
 */

namespace app\common\model;
use think\Model;

/**
 * 订单
 * Class Order
 * @package app\common\model
 */
class Order extends Model
{

    /**
     * 获取订单列表
     * @param array $condition
     * @param string $field
     * @param int $page
     * @param string $order
     * @return mixed
     */
   public function getOrderlist($condition = array(), $field = '*', $page = 0, $order = 'create_time desc'){
       if ($page) {
           $member_list = db('order')->field($field)->where($condition)->order($order)->paginate($page,false,['query' => request()->param()]);
           return $member_list;
       }
       else {
           return db('order')->field($field)->where($condition)->order($order)->select();
       }
   }
}