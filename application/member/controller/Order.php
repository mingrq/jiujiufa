<?php

namespace app\member\controller;

use app\common\controller\MemberBase;
use app\common\model\Goods;
use app\common\model\Member;
use app\common\model\MoneychangeRecord;
use app\common\model\Warehouse;

class Order extends MemberBase
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 礼品下单
     */
    public function payOrderPage()
    {
        $kdId = $this->request->param('gid') ? preg_replace('/[^0-9]/', '', $this->request->param('gid')) : 0;
        $goods = Goods::get($kdId);
        $goodsList = array();
        $classid = 0;
        if (!empty($goods) && !empty($goods['classId'])) {
            // $classid = $this->request->param('classid') ? preg_replace('/[^0-9]/', '', $this->request->param('classid')) : 0;
            $classid = $goods['classId'];
            $warehouse = Warehouse::get($classid);
            if (!empty($warehouse) && !empty($warehouse['wh_id'])) {
                // 调用仓库快递数据
                $goodsT = new Goods();
                $whereGoods['classId'] = $classid;
                $whereGoods['good_state'] = 1;
                $goodsList = $goodsT->where($whereGoods)->select();
            } else {

            }
        } else {
            $kdId = 0;
        }

        // 所有仓库
        $warehouseList = \db('warehouse')->select();

        $member = Member::get(session('MUserId'));

        $this->assign('classid', $classid);
        $this->assign('kdId', $kdId);
        $this->assign('memberBalance', $member['member_balance']);
        $this->assign('memberrank', $member['member_rank']);// 代理级别
        $this->assign('goodsList', $goodsList);
        $this->assign('warehouseList', $warehouseList);

        return $this->fetch();
    }

    /**
     * 根据classid（仓库ID）获取商品
     */
    public function getGoodsByCkId()
    {
        $goodsList = array();
        if ($this->request->isPost()) {
            $ckid = $this->request->post('ckid') ? preg_replace('/[^0-9]/', '', $this->request->post('ckid')) : 0;
            if (!empty($ckid)) {
                $goods = new Goods();
                $whereGoods['classId'] = $ckid;
                $whereGoods['good_state'] = 1;
                $goodsList = $goods->where($whereGoods)->select();
            }
        }
        $member = Member::get(session('MUserId'));
        if (empty($member) || empty($member['member_rank'])) {
            ds_json_encode(10001, "获取错误");
        } else {
            $result = array(
                "mrank" => $member['member_rank'],
                "goodsList" => $goodsList
            );
            ds_json_encode(10000, "商品获取成功", $result);
        }
    }

    /**
     * 下订单
     */
    public function buyOrder()
    {
        if ($this->request->isPost()) {
            $kdid = $this->request->post("kdid") ? preg_replace('/[^0-9]/', '', $this->request->post('kdid')) : 0;
            $ckid = $this->request->post("ckid") ? preg_replace('/[^0-9]/', '', $this->request->post('ckid')) : 0;
            $content = trim($this->request->post("content"));
            $whereGoods['gd.kdId'] = $kdid;
            $whereGoods['gd.classId'] = $ckid;
            $whereGoods['gd.good_state'] = 1;
            // 获取商品  判断商品信息否错误
            $goodsObj = new Goods();
            //$goods = $goodsObj->where($whereGoods)->find();
            $goods = $goodsObj->alias('gd')->field("gd.*, wh.wh_title as whTitle")->join("warehouse wh", "gd.classId=wh.wh_id", "left")->where($whereGoods)->find();
            if (empty($goods) || empty($goods['kdId'])) {
                // 商品ID错误
                ds_json_encode(10001, "商品信息错误", null);
            }
            // 获取用户级别
            $mUserId = session('MUserId');
            $member = Member::get($mUserId);
            if (empty($member) || empty($member['member_rank'])) {
                ds_json_encode(10004, "用户信息错误，请重新登录");
            }
            $mrank = $member['member_rank'];
            // 获取商品价格
            if ($mrank == 2) {
                $price = $goods['cost_price'] + $goods['good_vip_price'];
            } else {
                $price = $goods['cost_price'] + $goods['good_price'];
            }
            // 判断内容是否错误
            $param = array();
            if (empty($content)) {
                ds_json_encode(10002, "收货地址错误", null);
            }
            $contentArr = explode(PHP_EOL, $content);
            $errorNum = 0;
            foreach ($contentArr as $address) {
                $addressArr = explode("，", $address);
                if (count($addressArr) != 4) {
                    $errorNum++;
                }
                array_push($param, array(
                    'pid' => time() . rand(10000000, 99999999),
                    'msg' => $address,
                    'address' => trim($addressArr[2])
                ));
            }
            if ($errorNum > 0) {
                ds_json_encode(10003, "收货地址错误", $errorNum);
            }
            // 判断账号余额是否还够支付
            if ((count($contentArr) * $price) > $member['member_balance']) {
                ds_json_encode(10005, "账号余额不足", null);
            }
            // 提交订单
            $orderData = array();
            $mchRecordData = array();
            $order = new \app\common\model\Order();
            $result = $order->unifiedOrder($kdid, $param);
            if ($result['status'] == 'ok') {
                // 下单成功
                $kddhs = $result['kddhs'];
                $nowTime = date("Y-m-d H:i:s", time());

                // 订单全部
                for ($i = 0; $i < count($kddhs); $i++) {
                    $orderData[$i] = array(
                        'order_no' => $kddhs[$i]['pid'],
                        'member_id' => $mUserId,
                        'tracking_number' => $kddhs[$i]['num'],
                        'consignee_name' => $kddhs[$i]['takeName'],
                        'shipping_address' => $param[$i]['address'],
                        'express_name' => $goods['whTitle'],
                        'create_time' => $nowTime,
                        'order_pay' => $kddhs[$i]['price'],
                        'order_state' => 2,
                        'goodsTitle' => $goods['kdName'],
                        'consignee_phone' => $kddhs[$i]['takePhone']
                    );
                    $mchRecordData[$i] = array(
                        'member_id' => $mUserId,
                        'change_money' => (-1 * $kddhs[$i]['price']),
                        'change_time' => $nowTime,
                        'change_cause' => '购买小礼品：' . $goods['kdName']
                    );
                }
                //$orderT = new \app\common\model\Order();
                $res = $order->saveAll($orderData);
                if ($res > 0) {
                    // 资金变更记录
                    $moneychangeRecord = new MoneychangeRecord();
                    $moneychangeRecord->saveAll($mchRecordData);
                    // 更新余额
                    $nowMBalance = $member['member_balance'] - (count($contentArr) * $price);
                    //$member->member_balance = $nowMBalance;
                    $member->save([
                        'member_balance' => $nowMBalance
                    ], ['member_id' => $member['member_id']]);
                    ds_json_encode(10000, "下单成功");
                } else {
                    ds_json_encode(10007, "下单失败");
                }
            } else {
                ds_json_encode(10006, $result['status']);
            }
            //ds_json_encode(10000, "订单提交成功");
        } else {
            ds_json_encode(10010, "数据错误", null);
        }
    }

    /**
     * 订单列表
     */

    public function orderList()
    {
        if (request()->isPost()){
            $condition = array();
            $condition['member_id']=session("MUserId");
            $order_mod = model('order');
            $orderList = $order_mod->getOrderlist($condition);

            if ($orderList){
                ds_json_encode(10000, "获取订单列表成功", $orderList);
            }else{
                ds_json_encode(10001, "获取订单列表失败");
            }
        }else{
            return $this->fetch();
        }
    }



    /**
     * 搜索订单列表
     */
    public function searchorderlist()
    {
        $condition = array();
        $condition['member_id']=session("MUserId");

        $serach_param = input('param.searchparam');
        $find = array('\\', '/', '%', '_', '&');
        $replace = array('\\\\', '\\/', '\%', '\_', '\&');
        $serach_param = '%' . trim(str_replace($find, $replace, $serach_param)) . '%';
        if ($serach_param && trim($serach_param) != "") {
            $condition['order_no|tracking_number|consignee_name'] = ['like', $serach_param];
        }

        $dateatart = input('param.dateatart');
        $dateend = input('param.dateend');

        if ($dateatart && $dateend) {
            $condition["create_time"] = ['between', [$dateatart, $dateend]];
        } else {
            if ($dateatart) {
                $condition["create_time"] = ['>=', $dateatart];
            }

            if ($dateend) {
                $condition["create_time"] = ['<=', $dateend];
            }
        }

        $query = db('v_order')
            ->where($condition)
            ->order('create_time desc')
            ->paginate(100);
        if ($query) {
            ds_json_encode(10000, "搜索订单列表成功", $query);
        } else {
            ds_json_encode(10001, "搜索订单列表失败");
        }
    }


    /**
     * 删除已购买小礼品订单
     */
    public function delLpdh()
    {
        if ($this->request->isPost()) {
            $oid = $this->request->post("oid") ? preg_replace('/[^0-9]/', '', $this->request->post('oid')) : 0;
            $order = \app\common\model\Order::get($oid);
            if (empty($order) || empty($order['order_no'])) {
                ds_json_encode(10001, "订单信息错误", null);
            }
            $orderT = new \app\common\model\Order();
            $result = $orderT->delOrder($order['tracking_number']);
            if ($result['status'] == 'ok') {
                // 删除成功
                // 将这个订单状态修改成 4：已取消
                $order->order_state = 4;
                $order->save();
                ds_json_encode(10000, "删除成功");
            } else {
                // 删除失败
                ds_json_encode(10004, $result['status']);
            }
        } else {
            ds_json_encode(10010, "数据错误", null);
        }
    }

    /**
     * 申请底单
     */
    public function didan()
    {
        if (request()->isPost()) {
            $memberid = session("MUserId");
            $field = ['order_no', 'tracking_number','consignee_name','shipping_address','express_name','create_time','order_pay','order_state','goodsTitle','consignee_phone'];
            $query = db('order')->field($field)->where('order_state', 'in', [2, 3])->where('member_id', $memberid)->select();
           if ($query){
               ds_json_encode(10000, "获取底单成功", $query);
           }else{
               ds_json_encode(10001, "获取底单失败");
           }

        } else {
            return $this->fetch();
        }
    }
}