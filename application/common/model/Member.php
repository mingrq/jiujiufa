<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/16
 * Time: 9:00
 */

namespace app\common\model;


use think\Model;

class Member extends Model
{
    /**
     * 会员详细信息（查库）
     * @access public
     * @author csdeshang
     * @param array $condition 条件
     * @param string $field 字段
     * @return array
     */
    public function getMemberInfo($condition,$field='*')
    {
        $res = db('member')->field($field)->where($condition)->find();
        return $res;
    }

    /**
     * 会员列表
     * @access public
     * @author csdeshang
     * @param array $condition 条件
     * @param string $field    字段
     * @param number $page     分页
     * @param string $order    排序
     * @return array
     */
    public function getMemberList($condition = array(), $field = '*', $page = 0, $order = 'member_id desc')
    {
        if ($page) {
            $member_list = db('member')->field($field)->where($condition)->order($order)->paginate($page,false,['query' => request()->param()]);
            return $member_list;
        }
        else {
            return db('member')->field($field)->where($condition)->order($order)->select();
        }
    }

    /**
     * 获取充值记录
     * @param array $condition
     * @param string $field
     * @param int $page
     * @param string $order
     * @return mixed
     */
    public function getRechargeRecord($condition = array(), $field = '*', $page = 0, $order = 'recharge_time desc')
    {
        if ($page) {
            $recharge_record = db('recharge_record')->field($field)->where($condition)->order($order)->paginate($page,false,['query' => request()->param()]);
            return $recharge_record;
        }
        else {
            return db('recharge_record')->field($field)->where($condition)->order($order)->select();
        }
    }

    /**
     * 获取会员资金明细
     * @param array $condition
     * @param string $field
     * @param int $page
     * @param string $order
     * @return mixed
     */
    public function getMoneychangeRecord($condition = array(), $field = '*', $page = 0, $order = 'change_time desc')
    {
        if ($page) {
            $recharge_record = db('moneychange_record')->field($field)->where($condition)->order($order)->paginate($page,false,['query' => request()->param()]);
            return $recharge_record;
        }
        else {
            return db('moneychange_record')->field($field)->where($condition)->order($order)->select();
        }
    }


}