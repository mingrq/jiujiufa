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
    protected $pk = 'order_id';

    /**
     * 获取订单列表
     * @param array $condition
     * @param string $field
     * @param int $page
     * @param string $order
     * @return mixed
     */
    public function getOrderlist($condition = array(), $field = '*', $page = 100, $order = 'create_time desc')
    {
        if ($page) {
            $member_list = db('v_order')->field($field)->where($condition)->order($order)->paginate($page, false, ['query' => request()->param()]);
            return $member_list;
        } else {
            return db('v_order')->field($field)->where($condition)->order($order)->select();
        }
    }

    /**
     * 小礼品单号下单地址
     */
    public function unifiedOrder($param)
    {
        $url = 'http://www.681kb.com/API/BuyLpdh';
        $username = db('config')->where('config_code', 'goods_api_acc')->value('config_value');
        $pwd = db('config')->where('config_code', 'goods_api_pw')->value('config_value');

        $sid = time() . rand(10000000, 99999999);
        $pwd16 = substr(md5($pwd), 8, 16);
        $sign = strtolower(md5($username . $pwd16 . $sid));
        $info = array(
            'sid' => $sid,
            'sign' => $sign,
            'username' => $username
        );
        $postAddrItem = array(
            'postPhone' => '',
            'province' => '',
            'addr' => '',
            'area' => '',
            'postName' => 'city'
        );
        $data = array(
            'info' => $info,
            'kdid' => 1,
            'postAddrItem' => $postAddrItem,
            'num' => 1,
            'items' => $param
        );

        $json_str = request_post($url, $data);
        $json = json_decode($json_str, true);
        return $json;
    }
}