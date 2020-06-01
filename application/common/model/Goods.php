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
    public function getGoodsList($condition = array(), $field = '*', $page = 100, $order = 'kdId desc')
    {
        return db('v_goods')->field($field)->where($condition)->order($order)->paginate($page, false, ['query' => request()->param()]);
    }

    /**
     *
     * 获取特殊商品列表
     * @param int $page
     * @param string $order
     * @return mixed
     */
    public function getSpecialGoodsList($mid,$page = 100, $order = 'kdId desc')
    {
        $specialpricelist = db('special_price')->where('member_id', $mid)->fetchSql(true)->select();
        $query=db('v_onsale_goods')->alias('vgoods')->join('(' . $specialpricelist . ') specialprice', 'specialprice.kd_id = vgoods.kdId','LEFT')->field(['vgoods.*','specialprice.good_special_price','specialprice.good_special_vip_price','specialprice.good_special_api_price'])->order($order)->paginate($page, false, ['query' => request()->param()]);
        return $query;
    }
}