<?php
/**
 * Created by PhpStorm.
 * User: 002
 * Date: 2020/5/25
 * Time: 10:54
 */

namespace app\common\model;


use think\Model;

class Advertising extends Model
{
    /**
     * 获取广告列表
     */
    public function getadvertislist($condition = array(), $field = '*', $page = 100, $order = 'ad_id desc'){
        if ($page) {
            $member_list = db('v_advertising')->field($field)->where($condition)->order($order)->paginate($page, false, ['query' => request()->param()]);
            return $member_list;
        } else {
            return db('v_advertising')->field($field)->where($condition)->order($order)->select();
        }
    }

    /**
     * 获取广告分类
     */
    public function getaddverisingclass(){
       return db('advertising_class')->select();
    }
}