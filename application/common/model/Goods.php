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
     * 获取特殊价格商品列表
     * @param int $mid 用户id
     * @param int $page
     * @param string $order
     * @return mixed
     */
    public function getSpecialGoodsList($mid, $page = 100, $order = 'kdId desc')
    {
        $specialpricelist = db('special_price')->where('member_id', $mid)->fetchSql(true)->select();
        $query = db('v_onsale_goods')->alias('vgoods')->join('(' . $specialpricelist . ') specialprice', 'specialprice.kd_id = vgoods.kdId', 'LEFT')->where("vgoods.good_state", "=", 1)->field(['vgoods.*', 'specialprice.good_special_price', 'specialprice.good_special_vip_price', 'specialprice.good_special_api_price'])->order($order)->paginate($page);
        return $query;
    }

    /**
     * 前端 获取特殊价格商品列表
     * @param $mid
     * @param int $classId
     * @param string $order
     * @return mixed
     */
    public function FrontGetSpecialGoodsList($mid, $classId = 0, $order = 'kdId desc')
    {
        $whereGoods['vgoods.good_state'] = 1;
        $whereGoods['vgoods.classId'] = $classId;

        $specialpricelist = db('special_price')->where('member_id', $mid)->fetchSql(true)->select();
        $query = db('v_onsale_goods')->alias('vgoods')->join('(' . $specialpricelist . ') specialprice', 'specialprice.kd_id = vgoods.kdId', 'LEFT')->where($whereGoods)->field(['vgoods.*', 'specialprice.good_special_price', 'specialprice.good_special_vip_price', 'specialprice.good_special_api_price'])->order($order)->select();
        return $query;
    }

    /**
     * 首页 获取特殊价格商品列表
     * @param $mid
     * @param int $limits
     * @param int $limite
     * @param string $order
     * @return mixed
     */
    public function getIndexSpecialGoodsList($mid, $limits = 0, $limite = 10, $classId = 0, $order = 'kdId desc')
    {
        if ($classId == 0) {
            $specialpricelist = db('special_price')->where('member_id', $mid)->fetchSql(true)->select();
            $query = db('v_onsale_goods')->alias('vgoods')->join('(' . $specialpricelist . ') specialprice', 'specialprice.kd_id = vgoods.kdId', 'LEFT')->where("vgoods.good_state", "=", 1)->field(['vgoods.*', 'specialprice.good_special_price', 'specialprice.good_special_vip_price', 'specialprice.good_special_api_price'])->order($order)->limit($limits, $limite)->select();
        } else {
            $where['vgoods.good_state'] = 1;
            $where['vgoods.classId'] = $classId;

            $specialpricelist = db('special_price')->where('member_id', $mid)->fetchSql(true)->select();
            $query = db('v_onsale_goods')->alias('vgoods')->join('(' . $specialpricelist . ') specialprice', 'specialprice.kd_id = vgoods.kdId', 'LEFT')->where($where)->field(['vgoods.*', 'specialprice.good_special_price', 'specialprice.good_special_vip_price', 'specialprice.good_special_api_price'])->order($order)->limit($limits, $limite)->select();
        }
        return $query;
    }

    /**
     * 查询某一个特殊价格商品
     */
    public function findSpecialGoods($mid, $classId = 0, $kdId = 0)
    {
        $where['vgoods.good_state'] = 1;
        $where['vgoods.classId'] = $classId;
        $where['vgoods.kdId'] = $kdId;

        $specialpricelist = db('special_price')->where('member_id', $mid)->fetchSql(true)->select();
        $query = db('v_onsale_goods')->alias('vgoods')->join('(' . $specialpricelist . ') specialprice', 'specialprice.kd_id = vgoods.kdId', 'LEFT')->where($where)->field(['vgoods.*', 'specialprice.good_special_price', 'specialprice.good_special_vip_price', 'specialprice.good_special_api_price'])->find();

        return $query;
    }
}