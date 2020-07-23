<?php
/**
 * 订单相关接口
 */

namespace app\api\controller;

use app\common\model\Member;
use think\Exception;

class ApiOrder extends ApiBaseController
{
    private $username = '';

    /**
     * 初始化参数和身份验证
     */
    public function _initialize()
    {
        if ($this->request->isPost()) {
            $username = trim($this->request->post("username"));
            $nonce_str = trim($this->request->post("nonce_str"));
            $sign = trim($this->request->post("sign"));
            if (!empty($username) && !empty($nonce_str) && !empty($sign)) {
                $this->username = $username;
                if (!parent::verify_ticket($username, $nonce_str, $sign)) {
                    ds_json_encode(10002, "身份验证不通过，账号或密码错误", null);
                }
            } else {
                ds_json_encode(10003, "请求参数格式错误", null);
            }
        } else {
            ds_json_encode(10003, "请求参数格式错误", null);
        }
    }

    /**
     *  面单号 下单的接口
     *  将订单写入这个账号的订单记录中
     * @param $kdId 快递ID
     * @param $orderParams  订单内容的 json 格式，是一个json 的数组对象, 数组中一次最多不能超过 5 个订单
     */
    public function orderGift()
    {
        $kdId = preg_replace("/[^0-9]/", "", $this->request->post("kdId"));
        $orderParams = $this->request->post("orderParams");
        $orderParamList = json_decode($orderParams, true);
        $orderListTemp = array();
        if (!empty($kdId) && !empty($orderParamList) && count($orderParamList) <= 5) {
            // TODO 1、判断订单格式是否正确
            foreach ($orderParamList as $key => $order) {
                $orderFlag = 0; // 0 正常 1 订单参数错误 2 商品信息错误
                $storeType = preg_replace("/[^0-9]/", "", $order['storeType']);
                if (empty($order['buyerName']) || trim($order['buyerName']) == "" || empty($order['buyerMobile']) || trim($order['buyerMobile']) == "" || empty($order['buyerAddr']) || trim($order['buyerAddr']) == "" || empty($order['storeType']) || trim($order['storeType']) == "" || empty($storeType)) {
                    $orderFlag = 1;
                }
                // 获取商品  判断商品信息否错误
                $whereGoods['gd.kdId'] = $kdId;
                $whereGoods['wh.wh_class'] = $storeType;
                $whereGoods['gd.good_state'] = 1;
                $goodsObj = new Goods();
                //$goods = $goodsObj->where($whereGoods)->find();
                $goods = $goodsObj->alias('gd')->field("gd.*, wh.wh_title as whTitle")->join("warehouse wh", "gd.classId=wh.wh_id", "left")->where($whereGoods)->find();
                if (empty($goods) || empty($goods['kdId'])) {
                    $orderFlag = 2;
                }

                $costPrice = $goods['cost_price'];

                $orderListTemp[$key] = array(
                    "apiOrderId" => time() . rand(10000000, 99999999),
                    "buyerName" => trim($order['buyerName']),
                    "buyerMobile" => trim($order['buyerMobile']),
                    "buyerAddr" => trim($order['buyerAddr']),
                    "storeType" => $storeType,
                    "kuaidiName" => trim($order['kuaidiName'])
                );
            }
            // TODO 2、判断商品是否存在 kdId storeType kuaidiName 三个条件

            // TODO 3、判断账户金额是否不足
        } else {
            // 订单orderParamsjson 的数组对象超过 5 个订单
            ds_json_encode(10003, "请求参数格式错误", null);
        }
    }
}