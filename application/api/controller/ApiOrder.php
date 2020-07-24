<?php
/**
 * 订单相关接口
 */

namespace app\api\controller;

use app\common\model\Member;
use app\common\model\Order;

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
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     *
     * 逻辑：
     *  1、判断参数格式是否正确 不正确返回"请求参数格式错误"
     *  2、根据kdId获取商品和仓库信息 如果商品信息不正确返回"订单信息错误"
     *  3、判断订单参数中仓库参数和商品的仓库信息是否相同 如果商不相同返回"订单信息错误"
     *  4、如果都正确最后判断账号余额是否足 如果不足返回"账户余额不足"
     */
    public function orderGift()
    {
        $kdId = preg_replace("/[^0-9]/", "", $this->request->post("kdId"));
        $orderParams = $this->request->post("orderParams");
        $orderParamList = json_decode($orderParams, true);
        $orderListTemp = array();
        if (!empty($kdId) && !empty($orderParamList) && count($orderParamList) <= 5) {
            $orderFlag = true;
            // 查询商品和仓库信息
            $whereGoods['gd.kdId'] = $kdId;
            $whereGoods['gd.good_state'] = 1;
            $goodsObj = new Goods();
            $goods = $goodsObj->alias('gd')->field("gd.*, wh.wh_title as whTitle, wh.wh_alias as whAlias, wh.wh_class as whClass")->join("warehouse wh", "gd.classId=wh.wh_id", "left")->where($whereGoods)->find();
            if (empty($goods) || empty($goods['kdId']) || empty($goods['whAlias']) || empty($goods['whClass'])) {
                ds_json_encode(10004, "订单信息错误", null);
            }
            // 判断订单参数中仓库参数和商品的仓库信息是否相同
            foreach ($orderParamList as $key => $order) {
                $storeType = preg_replace("/[^0-9]/", "", $order['storeType']);
                $kuaidiName = trim($order['kuaidiName']);
                if ($goods['whClass'] != $storeType || $goods['whAlias'] != $kuaidiName) {
                    $orderFlag = false;
                    break;
                }
                // 判断订单格式是否正确
                if (empty($order['buyerName']) || trim($order['buyerName']) == "" || empty($order['buyerMobile']) || trim($order['buyerMobile']) == "" || empty($order['buyerAddr']) || trim($order['buyerAddr']) == "" || empty($storeType) || empty($kuaidiName)) {
                    $orderFlag = false;
                    break;
                }

                $orderListTemp[$key] = array(
                    "apiOrderId" => time() . rand(10000000, 99999999),
                    "buyerName" => trim($order['buyerName']),
                    "buyerMobile" => trim($order['buyerMobile']),
                    "buyerAddr" => trim($order['buyerAddr']),
                    "buyerAddrCode" => empty($order['buyerAddrCode']) ? '' : trim($order['buyerAddrCode']),
                    "storeType" => $storeType,
                    "kuaidiName" => $kuaidiName
                );
            }
            if (!$orderFlag) {
                ds_json_encode(10004, "订单信息错误", null);
            }
            // 判断账户金额是否不足
            // 根据username查询member_id
            $member = db("member")->field('id,member_balance')->where('member_login_name', '=', $this->username)->find();
            // 查询特殊价格
            $wherePrice['member_id'] = $member['member_id'];
            $wherePrice['kd_id'] = $kdId;
            $specialPrice = db("special_price")->where($wherePrice)->find();
            // 成本
            $costPrice = $goods['cost_price'];
            if (!empty($specialPrice) && !empty($specialPrice['good_special_api_price'])) {
                $orderProfit = $specialPrice['good_special_api_price'];
                $price = $costPrice + $orderProfit;
            } else {
                $orderProfit = $goods['good_api_price'];
                $price = $costPrice + $orderProfit;
            }
            if ((count($orderListTemp) * $price) > $member['member_balance']) {
                ds_json_encode(10005, "账户余额不足", null);
            }
            // 调用接口下订单
            $nowTime = date("Y-m-d H:i:s", time());
            $order = new Order();
            $result = $order->unifiedOrder($goods['good_id'], $orderListTemp);
            // 判断结果
            $orderData = array();
            $mchRecordData = array();
            if ($result['result'] == 1) {
                $orders = $result['orders'];
                for ($i = 0; $i < count($orders); $i++) {
                    if ($orders[$i]['orderResult'] == 1) {
                        array_push($orderData, array(
                            'order_no' => $orders[$i]['apiOrderId'],
                            'member_id' => $member['member_id'],
                            'tracking_number' => $orders[$i]['expressNo'],
                            'consignee_name' => $orderListTemp[$i]['buyerName'],
                            'shipping_address' => $orderListTemp[$i]['buyerAddr'],
                            'express_name' => $goods['whTitle'],
                            'create_time' => $nowTime,
                            'order_pay' => $price,
                            'order_state' => 3,
                            'goodsTitle' => $goods['kdName'],
                            'consignee_phone' => $orderListTemp[$i]['buyerMobile'],
                            'merchant_order_no' => "api",
                            'postcode' => $orderListTemp[$i]['buyerAddrCode'],
                            'order_cost' => $costPrice,
                            'order_profit' => $orderProfit
                        ));
                        array_push($mchRecordData, array(
                            'member_id' => $member['member_id'],
                            'change_money' => (-1 * $price),
                            'change_time' => $nowTime,
                            'change_cause' => '购买小礼品：' . $goods['kdName']
                        ));
                    }
                }
                // 订单记录
                $res = $order->saveAll($orderData);
                if ($res > 0) {
                    // 更新余额
                    $nowMBalance = $member['member_balance'] - (count($orderListTemp) * $price);
                    $member->save([
                        'member_balance' => $nowMBalance
                    ], ['member_id' => $member['member_id']]);
                    // 资金变更记录
                    $moneychangeRecord = new MoneychangeRecord();
                    $moneychangeRecord->saveAll($mchRecordData);
                }
                ds_json_encode(1, "成功", $orders);
            } else {
                // 下单失败
                ds_json_encode(10010, "订单下单失败", null);
            }
        } else {
            // 订单orderParamsjson 的数组对象超过 5 个订单
            ds_json_encode(10003, "请求参数格式错误", null);
        }
    }
}