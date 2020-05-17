<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/17
 * Time: 23:36
 */

namespace app\common\model;

use think\Model;

/**
 * 商品
 * Class Goods
 * @package app\common\model
 */
class Goods extends Model
{
    /**
     *
     * 获取商品列表
     * @param array $condition
     * @param string $field
     * @param int $page
     * @param string $order
     * @return mixed
     */
    public function getGoodsList($condition = array(), $field = '*', $page = 0, $order = 'good_id desc')
    {
        if ($page) {
            $member_list = db('goods')->field($field)->where($condition)->order($order)->paginate($page, false, ['query' => request()->param()]);
            return $member_list;
        } else {
            return db('goods')->field($field)->where($condition)->order($order)->select();
        }
    }


}