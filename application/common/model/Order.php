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
     * goodId   商品ID
     * param    数组参数
     */
    public function unifiedOrder($goodId, $param)
    {
        $partnerId = 7863921;
        $secret = "ac64e3a36a784aabb3c48f7f62fb4a5c";

        //下单
        $url = "http://182.254.212.247:9103/ApiOrder/orderGift";

        $orderParams = json_encode($param);
        // 对身份的验证
        $validation = md5($goodId . $orderParams . $partnerId . $secret);

        $data = array(
            'partnerId' => $partnerId,
            'itemId' => $goodId,
            'orderParams' => $orderParams,
            'validation' => $validation
        );

        $json_str = request_post_arr($url, $data);
        $json = json_decode($json_str, true);
        return $json;


//        $url = 'http://www.681kb.com/API/BuyLpdh';
//        $username = db('config')->where('config_code', 'goods_api_acc')->value('config_value');
//        $pwd = db('config')->where('config_code', 'goods_api_pw')->value('config_value');
//
//        $sid = time() . rand(10000000, 99999999);
//        $pwd16 = substr(md5($pwd), 8, 16);
//        $sign = strtolower(md5($username . $pwd16 . $sid));
//        $info = array(
//            'sid' => $sid,
//            'sign' => $sign,
//            'username' => $username
//        );
//        $postAddrItem = array(
//            'postPhone' => '',
//            'province' => '',
//            'addr' => '',
//            'area' => '',
//            'postName' => 'city'
//        );
//        $data = array(
//            'info' => $info,
//            'kdid' => $kdid,
//            'postAddrItem' => $postAddrItem,
//            'num' => 1,
//            'items' => $param
//        );
//
//        $json_str = request_post($url, $data);
//        $json = json_decode($json_str, true);
//        return $json;


//        //获取token
//        $md5Secret= md5($secret);
//        $get_token_url = "http://182.254.212.247:9103/ApiOrder/getToken?partnerId=".$partnerId."&md5Secret=".$md5Secret;
//        $json_str = http_request($get_token_url);

        //ds_json_encode(10001, "获取token:" . $kdid, $param);

    }

    /**
     * 删除已购买小礼品
     */
    public function delOrder($kddh)
    {
        $url = 'http://www.681kb.com/API/DelLpdh';
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

        $data = array(
            'info' => $info,
            'kddh' => $kddh
        );

        $json_str = request_post($url, $data);
        $json = json_decode($json_str, true);
        return $json;
    }
}
