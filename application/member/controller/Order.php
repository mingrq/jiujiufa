<?php

namespace app\member\controller;

use app\common\controller\MemberBase;
use app\common\model\Goods;
use app\common\model\Member;
use app\common\model\MoneychangeRecord;
use app\common\model\Warehouse;
use think\Loader;

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
        $warehouseList = array();
        $specialGoods = array();

        // 获取所有的仓库
        $warehouseClassList = db("wasehouse_class")->order('class_id', "asc")->select();
        foreach ($warehouseClassList as $key => $warehouseClass) {
            $warehouseList[$key]['classId'] = $warehouseClass['class_id'];
            $warehouseList[$key]['className'] = $warehouseClass['class_name'];
            $warehouseList[$key]['classRemark'] = $warehouseClass['class_remark'];
            $warehouseTemp = db("warehouse")->where("wh_class", "=", $warehouseClass['class_id'])->select();
            if (!empty($warehouseTemp) && count($warehouseTemp) > 0) {
                $warehouseList[$key]['children'] = $warehouseTemp;
            } else {
                $warehouseList[$key]['children'] = [];
            }
        }

        if (!empty($goods) && !empty($goods['classId'])) {
            // $classid = $this->request->param('classid') ? preg_replace('/[^0-9]/', '', $this->request->param('classid')) : 0;
            $classid = $goods['classId'];
            $specialGoods = $goods->findSpecialGoods(session('MUserId'), $classid, $kdId);
        } else {
            $classid = $warehouseClassList[0]['class_id'];
            $kdId = 0;
        }

        $member = Member::get(session('MUserId'));

        // 当前仓库下的产品
        $warehouse = Warehouse::get($classid);
        if (!empty($warehouse) && !empty($warehouse['wh_id'])) {
            // 调用仓库快递数据

            $goodsT = new Goods();
            //$whereGoods['classId'] = $classid;
            //$whereGoods['good_state'] = 1;
            //$goodsList = $goodsT->where($whereGoods)->select();
            $goodsList = $goodsT->FrontGetSpecialGoodsList(session('MUserId'), $classid);
        }

        $this->assign('classid', $classid);
        $this->assign('kdId', $kdId);
        $this->assign('memberBalance', $member['member_balance']);
        $this->assign('memberrank', $member['member_rank']);// 代理级别
        $this->assign('goodsList', $goodsList);
        $this->assign('goods', $specialGoods);
        $this->assign('warehouseList', $warehouseList);

        return $this->fetch();
    }

    /**
     * 查询单个商品特殊价格
     */
    public function findSingleSpecialGoods()
    {
        if ($this->request->isPost()) {
            $kdId = $this->request->post('gid') ? preg_replace('/[^0-9]/', '', $this->request->param('gid')) : 0;
            $classId = $this->request->post('ckid') ? preg_replace('/[^0-9]/', '', $this->request->param('ckid')) : 0;
            if (!empty($kdId) && !empty($classId)) {
                $goods = new Goods();
                $specialGoods = $goods->findSpecialGoods(session('MUserId'), $classId, $kdId);
                if (!empty($specialGoods) && !empty($specialGoods['kdId'])) {

                    $member = Member::get(session('MUserId'));
                    if (empty($member) || empty($member['member_rank'])) {
                        ds_json_encode(10004, "会员信息获取错误");
                    }

                    $result = array(
                        'mrank' => $member['member_rank'],
                        'goods' => $specialGoods
                    );
                    ds_json_encode(10000, "获取成功", $result);
                } else {
                    ds_json_encode(10003, "获取失败", null);
                }
            } else {
                ds_json_encode(10001, "获取失败", null);
            }
        } else {
            ds_json_encode(10002, "获取失败", null);
        }
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
                // $whereGoods['classId'] = $ckid;
                // $whereGoods['good_state'] = 1;
                // $goodsList = $goods->where($whereGoods)->select();
                $goodsList = $goods->FrontGetSpecialGoodsList(session('MUserId'), $ckid);
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
     * 导入文件
     */
    /*public function uploadKdModel()
    {
        $file = $this->request->file("uploadkdodfile");
        if ($file) {
            $info = $file->validate(['size' => 5242880, 'ext' => 'xls,xlsx'])->move(ROOT_PATH . 'public' . DS . 'files/upload');
            if ($info) {
                $file_name = $info->getSaveName();
                $file_path = ROOT_PATH . 'public' . DS . 'files/upload' . DS . $file_name;

                // 上传成功后 将数据读取出来 返回
                Loader::import("PHPExcel.PHPExcel");
                Loader::import('PHPExcel.PHPExcel.IOFactory.PHPExcel_IOFactory');

                if ($info->getExtension() == 'xls') {
                    Loader::import('PHPExcel.PHPExcel.Reader.Excel5');
                    $objReader = \PHPExcel_IOFactory::createReader('Excel5');
                } else if ($info->getExtension() == 'xlsx') {
                    Loader::import('PHPExcel.PHPExcel.Reader.Excel2007');
                    $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
                } else {
                    ds_json_encode(10003, "数据读取失败");
                }

                // $obj_PHPExcel = $objReader->load($file_path, $encode = 'utf-8');
                $obj_PHPExcel = $objReader->load($file_path);
                $excel_array = $obj_PHPExcel->getsheet(0)->toArray();

                $excel_data = [];
                if (count($excel_array[0]) == 4) {
                    // 淘宝京东
                    array_shift($excel_array);  //删除第一个数组(标题);
                    foreach ($excel_array as $k => $v) {
                        $excel_data[$k]['kdid'] = $v[0];
                        $excel_data[$k]['name'] = $v[1];
                        $excel_data[$k]['mobile'] = $v[2];
                        $excel_data[$k]['address'] = $v[3];
                        $excel_data[$k]['postal'] = '000000';
                    }
                } else if (count($excel_array[0]) == 7) {
                    // 拼多多
                    array_shift($excel_array);  //删除第一个数组(标题);
                    foreach ($excel_array as $k => $v) {
                        $excel_data[$k]['kdid'] = $v[0];
                        $excel_data[$k]['name'] = $v[1];
                        $excel_data[$k]['mobile'] = $v[2];
                        $excel_data[$k]['address'] = $v[3] . $v[4] . $v[5] . $v[6];
                        $excel_data[$k]['postal'] = '000000';
                    }
                } else {
                    ds_json_encode(10004, "数据格式错误");
                }
                ds_json_encode(10000, "上传成功", $excel_data);
            } else {
                ds_json_encode(10001, "上传失败");
            }
        } else {
            ds_json_encode(10002, "上传失败");
        }
    }*/

    /**
     * 导入淘宝京东表格
     */
    public function importTbjd()
    {
        $file = $this->request->file("uploadkdodfile");
        if ($file) {
            $info = $file->validate(['size' => 5242880, 'ext' => 'xls,xlsx'])->move(ROOT_PATH . 'public' . DS . 'files/upload');
            if ($info) {
                $file_name = $info->getSaveName();
                $file_path = ROOT_PATH . 'public' . DS . 'files/upload' . DS . $file_name;

                // 上传成功后 将数据读取出来 返回
                Loader::import("PHPExcel.PHPExcel");
                Loader::import('PHPExcel.PHPExcel.IOFactory.PHPExcel_IOFactory');

                if ($info->getExtension() == 'xls') {
                    Loader::import('PHPExcel.PHPExcel.Reader.Excel5');
                    $objReader = \PHPExcel_IOFactory::createReader('Excel5');
                } else if ($info->getExtension() == 'xlsx') {
                    Loader::import('PHPExcel.PHPExcel.Reader.Excel2007');
                    $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
                } else {
                    ds_json_encode(10003, "数据读取失败");
                }

                // $obj_PHPExcel = $objReader->load($file_path, $encode = 'utf-8');
                $obj_PHPExcel = $objReader->load($file_path);
                $excel_array = $obj_PHPExcel->getsheet(0)->toArray();

                $excel_data = [];
                if (count($excel_array[0]) == 4) {
                    // 淘宝京东
                    array_shift($excel_array);  //删除第一个数组(标题);
                    foreach ($excel_array as $k => $v) {
                        $excel_data[$k]['kdid'] = $v[0];
                        $excel_data[$k]['name'] = $v[1];
                        $excel_data[$k]['mobile'] = preg_replace('/[^0-9]/', '', $v[2]);
                        $excel_data[$k]['address'] = $v[3];
                        $excel_data[$k]['postal'] = '000000';
                    }
                } else {
                    ds_json_encode(10004, "数据格式错误");
                }
                /*
                if (count($excel_array[0]) == 4) {
                    // 淘宝京东
                    array_shift($excel_array);  //删除第一个数组(标题);
                    foreach ($excel_array as $k => $v) {
                        $excel_data[$k]['kdid'] = $v[0];
                        $excel_data[$k]['name'] = $v[1];
                        $excel_data[$k]['mobile'] = $v[2];
                        $excel_data[$k]['address'] = $v[3];
                        $excel_data[$k]['postal'] = '000000';
                    }
                } else if (count($excel_array[0]) == 7) {
                    // 拼多多
                    array_shift($excel_array);  //删除第一个数组(标题);
                    foreach ($excel_array as $k => $v) {
                        $excel_data[$k]['kdid'] = $v[0];
                        $excel_data[$k]['name'] = $v[1];
                        $excel_data[$k]['mobile'] = $v[2];
                        $excel_data[$k]['address'] = $v[3] . $v[4] . $v[5] . $v[6];
                        $excel_data[$k]['postal'] = '000000';
                    }
                } else {
                    ds_json_encode(10004, "数据格式错误");
                }
                */
                ds_json_encode(10000, "上传成功", $excel_data);
            } else {
                ds_json_encode(10001, "上传失败");
            }
        } else {
            ds_json_encode(10002, "上传失败");
        }
    }

    /**
     * 导入拼多多专用表格
     */
    public function importPindd()
    {
        $file = $this->request->file("uploadkdodfile");
        if ($file) {
            $info = $file->validate(['size' => 5242880, 'ext' => 'xls,xlsx'])->move(ROOT_PATH . 'public' . DS . 'files/upload');
            if ($info) {
                $file_name = $info->getSaveName();
                $file_path = ROOT_PATH . 'public' . DS . 'files/upload' . DS . $file_name;

                // 上传成功后 将数据读取出来 返回
                Loader::import("PHPExcel.PHPExcel");
                Loader::import('PHPExcel.PHPExcel.IOFactory.PHPExcel_IOFactory');

                if ($info->getExtension() == 'xls') {
                    Loader::import('PHPExcel.PHPExcel.Reader.Excel5');
                    $objReader = \PHPExcel_IOFactory::createReader('Excel5');
                } else if ($info->getExtension() == 'xlsx') {
                    Loader::import('PHPExcel.PHPExcel.Reader.Excel2007');
                    $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
                } else {
                    ds_json_encode(10003, "数据读取失败");
                }

                // $obj_PHPExcel = $objReader->load($file_path, $encode = 'utf-8');
                $obj_PHPExcel = $objReader->load($file_path);
                $excel_array = $obj_PHPExcel->getsheet(0)->toArray();

                $excel_data = [];
                if (count($excel_array[0]) == 7) {
                    // 拼多多
                    array_shift($excel_array);  //删除第一个数组(标题);
                    foreach ($excel_array as $k => $v) {
                        $excel_data[$k]['kdid'] = $v[0];
                        $excel_data[$k]['name'] = $v[1];
                        $excel_data[$k]['mobile'] = preg_replace('/[^0-9]/', '', $v[2]);
                        $excel_data[$k]['address'] = $v[3] . $v[4] . $v[5] . $v[6];
                        $excel_data[$k]['postal'] = '000000';
                    }
                } else {
                    ds_json_encode(10004, "数据格式错误");
                }
                ds_json_encode(10000, "上传成功", $excel_data);
            } else {
                ds_json_encode(10001, "上传失败");
            }
        } else {
            ds_json_encode(10002, "上传失败");
        }
    }

    /**
     * 导入淘宝后台表格
     */
    public function importTaobao()
    {
        $file = $this->request->file("uploadkdodfile");
        if ($file) {
            $info = $file->validate(['size' => 5242880, 'ext' => 'xls,xlsx'])->move(ROOT_PATH . 'public' . DS . 'files/upload');
            if ($info) {
                $file_name = $info->getSaveName();
                $file_path = ROOT_PATH . 'public' . DS . 'files/upload' . DS . $file_name;

                // 上传成功后 将数据读取出来 返回
                Loader::import("PHPExcel.PHPExcel");
                Loader::import('PHPExcel.PHPExcel.IOFactory.PHPExcel_IOFactory');

                if ($info->getExtension() == 'xls') {
                    Loader::import('PHPExcel.PHPExcel.Reader.Excel5');
                    $objReader = \PHPExcel_IOFactory::createReader('Excel5');
                } else if ($info->getExtension() == 'xlsx') {
                    Loader::import('PHPExcel.PHPExcel.Reader.Excel2007');
                    $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
                } else {
                    ds_json_encode(10003, "数据读取失败");
                }

                // $obj_PHPExcel = $objReader->load($file_path, $encode = 'utf-8');
                $obj_PHPExcel = $objReader->load($file_path);
                $excel_array = $obj_PHPExcel->getsheet(0)->toArray();

                $excel_data = [];
                array_shift($excel_array);  //删除第一个数组(标题);
                foreach ($excel_array as $k => $v) {
                    $excel_data[$k]['kdid'] = $v[0];
                    $excel_data[$k]['name'] = $v[12];
                    $excel_data[$k]['mobile'] = preg_replace('/[^0-9]/', '', $v[16]);
                    $excel_data[$k]['address'] = $v[13];
                    $excel_data[$k]['postal'] = '000000';
                }
                ds_json_encode(10000, "上传成功", $excel_data);
            } else {
                ds_json_encode(10001, "上传失败");
            }
        } else {
            ds_json_encode(10002, "上传失败");
        }
    }

    /**
     * 导入京东后台表格
     */
    public function importJingd()
    {
        $file = $this->request->file("uploadkdodfile");
        if ($file) {
            $info = $file->validate(['size' => 5242880, 'ext' => 'xls,xlsx'])->move(ROOT_PATH . 'public' . DS . 'files/upload');
            if ($info) {
                $file_name = $info->getSaveName();
                $file_path = ROOT_PATH . 'public' . DS . 'files/upload' . DS . $file_name;

                // 上传成功后 将数据读取出来 返回
                Loader::import("PHPExcel.PHPExcel");
                Loader::import('PHPExcel.PHPExcel.IOFactory.PHPExcel_IOFactory');

                if ($info->getExtension() == 'xls') {
                    Loader::import('PHPExcel.PHPExcel.Reader.Excel5');
                    $objReader = \PHPExcel_IOFactory::createReader('Excel5');
                } else if ($info->getExtension() == 'xlsx') {
                    Loader::import('PHPExcel.PHPExcel.Reader.Excel2007');
                    $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
                } else {
                    ds_json_encode(10003, "数据读取失败");
                }

                // $obj_PHPExcel = $objReader->load($file_path, $encode = 'utf-8');
                $obj_PHPExcel = $objReader->load($file_path);
                $excel_array = $obj_PHPExcel->getsheet(0)->toArray();

                $excel_data = [];
                array_shift($excel_array);  //删除第一个数组(标题);
                foreach ($excel_array as $k => $v) {
                    $excel_data[$k]['kdid'] = $v[0];
                    $excel_data[$k]['name'] = $v[14];
                    $excel_data[$k]['mobile'] = preg_replace('/[^0-9]/', '', $v[16]);
                    $excel_data[$k]['address'] = $v[15];
                    $excel_data[$k]['postal'] = '000000';
                }
                ds_json_encode(10000, "上传成功", $excel_data);
            } else {
                ds_json_encode(10001, "上传失败");
            }
        } else {
            ds_json_encode(10002, "上传失败");
        }
    }

    /**
     * 导入拼多多订单
     */
    public function importPingddOrder()
    {
        $file = $this->request->file("uploadkdodfile");
        if ($file) {
            $info = $file->validate(['size' => 5242880, 'ext' => 'xls,xlsx'])->move(ROOT_PATH . 'public' . DS . 'files/upload');
            if ($info) {
                $file_name = $info->getSaveName();
                $file_path = ROOT_PATH . 'public' . DS . 'files/upload' . DS . $file_name;

                // 上传成功后 将数据读取出来 返回
                Loader::import("PHPExcel.PHPExcel");
                Loader::import('PHPExcel.PHPExcel.IOFactory.PHPExcel_IOFactory');

                if ($info->getExtension() == 'xls') {
                    Loader::import('PHPExcel.PHPExcel.Reader.Excel5');
                    $objReader = \PHPExcel_IOFactory::createReader('Excel5');
                } else if ($info->getExtension() == 'xlsx') {
                    Loader::import('PHPExcel.PHPExcel.Reader.Excel2007');
                    $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
                } else {
                    ds_json_encode(10003, "数据读取失败");
                }

                // $obj_PHPExcel = $objReader->load($file_path, $encode = 'utf-8');
                $obj_PHPExcel = $objReader->load($file_path);
                $excel_array = $obj_PHPExcel->getsheet(0)->toArray();

                $excel_data = [];
                array_shift($excel_array);  //删除第一个数组(标题);
                foreach ($excel_array as $k => $v) {
                    $excel_data[$k]['kdid'] = $v[1];
                    $excel_data[$k]['name'] = $v[15];
                    $excel_data[$k]['mobile'] = preg_replace('/[^0-9]/', '', $v[16]);
                    $excel_data[$k]['address'] = $v[18] . $v[19] . $v[20] . $v[21];
                    $excel_data[$k]['postal'] = '000000';
                }
                ds_json_encode(10000, "上传成功", $excel_data);
            } else {
                ds_json_encode(10001, "上传失败");
            }
        } else {
            ds_json_encode(10002, "上传失败");
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
            $costPrice = $goods['cost_price'];
            // 获取用户级别
            $mUserId = session('MUserId');
            $member = Member::get($mUserId);
            if (empty($member) || empty($member['member_rank'])) {
                ds_json_encode(10004, "用户信息错误，请重新登录");
            }
            $mrank = $member['member_rank'];

            $wherePrice['member_id'] = $mUserId;
            $wherePrice['kd_id'] = $kdid;
            $specialPrice = db("special_price")->where($wherePrice)->find();
            // 获取商品价格
            if ($mrank == 1) {
                if (!empty($specialPrice) && !empty($specialPrice['good_special_price'])) {
                    $orderProfit = $specialPrice['good_special_price'];
                    $price = $costPrice + $orderProfit;
                } else {
                    $orderProfit = $goods['good_price'];
                    $price = $costPrice + $orderProfit;
                }
            } else if ($mrank == 2) {
                if (!empty($specialPrice) && !empty($specialPrice['good_special_vip_price'])) {
                    $orderProfit = $specialPrice['good_special_vip_price'];
                    $price = $costPrice + $orderProfit;
                } else {
                    $orderProfit = $goods['good_price'];
                    $price = $costPrice + $orderProfit;
                }
            } else {
                ds_json_encode(10008, "会员代理级别错误", null);
            }
            /*
            if ($mrank == 2) {
                $price = $goods['cost_price'] + $goods['good_vip_price'];
            } else {
                $price = $goods['cost_price'] + $goods['good_price'];
            }
            */

            // 判断内容是否错误
            $param = array();
            $mchordernoarr = array();
            if (empty($content)) {
                ds_json_encode(10002, "收货地址错误", null);
            }
            $contentArr = explode(PHP_EOL, $content);
            $errorNum = 0;


            $wh_alias = db('v_goods')->where('kdId', $kdid)->find('wh_alias');
            foreach ($contentArr as $address) {
                $addressArr = explode("，", $address);
                if (count($addressArr) != 5) {
                    $errorNum++;
                } else {
                    // 去除订单号后 重新拼接
                    array_push($param, array(
                        'apiOrderId' => time() . rand(10000000, 99999999),
                        'buyerName' => $addressArr[1],
                        'buyerMobile' => $addressArr[2],
                        'buyerAddr' => $addressArr[3],
                        'buyerAddrCode' => $addressArr[4],
                        'storeType' => $ckid,
                        'kuaidiName' => $wh_alias['wh_alias']
                    ));
                    $mchordernoarr[] = trim($addressArr[0]);
                }
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
            $result = $order->unifiedOrder($wh_alias['good_id'], $param);

            dump($result);

            /*
            if ($result['result'] == 1) {

            }
            */

            /*
            if ($result['status'] == 'ok') {
                // 下单成功
                $kddhs = $result['kddhs'];
                $nowTime = date("Y-m-d H:i:s", time());

                // 订单全部
                for ($i = 0; $i < count($kddhs); $i++) {
                    $orderData[$i] = array(
                        'order_no' => $kddhs[$i]['apiOrderId'],
                        'member_id' => $mUserId,
                        'tracking_number' => $kddhs[$i]['num'],
                        'consignee_name' => $kddhs[$i]['buyerName'],
                        'shipping_address' => $param[$i]['buyerAddr'],
                        'express_name' => $goods['whTitle'],
                        'create_time' => $nowTime,
                        'order_pay' => $price,
                        'order_state' => 2,
                        'goodsTitle' => $goods['kdName'],
                        'consignee_phone' => $kddhs[$i]['buyerMobile'],
                        'merchant_order_no' => $mchordernoarr[$i],
                        'postcode' => $param[$i]['buyerAddrCode'],
                        'order_cost' => $costPrice,
                        'order_profit' => $orderProfit
                    );
                    $mchRecordData[$i] = array(
                        'member_id' => $mUserId,
                        'change_money' => (-1 * $price),
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
            */
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
        if (request()->isPost()) {
            $condition = array();
            $condition['member_id'] = session("MUserId");
            $order_mod = model('order');
            $orderList = $order_mod->getOrderlist($condition);

            if ($orderList) {
                ds_json_encode(10000, "获取订单列表成功", $orderList);
            } else {
                ds_json_encode(10001, "获取订单列表失败");
            }
        } else {
            return $this->fetch();
        }
    }


    /**
     * 搜索订单列表
     */
    public function searchorderlist()
    {
        $condition = array();
        $condition['member_id'] = session("MUserId");

        $serach_param = input('param.searchparam');
        $find = array('\\', '/', '%', '_', '&');
        $replace = array('\\\\', '\\/', '\%', '\_', '\&');
        $serach_param = '%' . trim(str_replace($find, $replace, $serach_param)) . '%';
        if ($serach_param && trim($serach_param) != "") {
            $condition['tracking_number|consignee_name|merchant_order_no'] = ['like', $serach_param];
        }

        // $dateatart = input('param.dateatart');
        // $dateend = input('param.dateend');
        $dateatart = $this->request->post("dateatart");
        $dateend = $this->request->post("dateend");

        if ($dateatart && $dateend) {
            $condition["create_time"] = ['between', [$dateatart . " 00:00:00", $dateend . " 23:59:59"]];
        } else {
            if ($dateatart) {
                $condition["create_time"] = ['>=', $dateatart . " 00:00:00"];
            }
            if ($dateend) {
                $condition["create_time"] = ['<=', $dateend . " 23:59:59"];
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
                db('member')->where('member_id', $order['member_id'])->setInc('member_balance', $order['order_pay']);
                db('moneychange_record')->insert(['member_id' => $order['member_id'], 'change_money' => $order['order_pay'], 'change_cause' => '订单退款']);
                ds_json_encode(10000, "删除成功");
            } else {
                // 删除失败
                $order->order_state = 3;
                $order->save();
                ds_json_encode(10001, '订单已发货，无法删除');
            }
        } else {
            ds_json_encode(10010, "数据错误", null);
        }
    }

    /**
     * 下载查询的订单
     */
    public function downOrder()
    {
        if (request()->isPost()) {
            $typeId = $this->request->post("typeId") ? preg_replace('/[^0-9]/', '', $this->request->post('typeId')) : 0;
            $condition = array();
            $condition['member_id'] = session("MUserId");

            $serach_param = input('param.searchparam');
            $find = array('\\', '/', '%', '_', '&');
            $replace = array('\\\\', '\\/', '\%', '\_', '\&');
            $serach_param = '%' . trim(str_replace($find, $replace, $serach_param)) . '%';
            if ($serach_param && trim($serach_param) != "") {
                $condition['tracking_number|consignee_name|merchant_order_no'] = ['like', $serach_param];
            }

            // $dateatart = input('param.dateatart');
            // $dateend = input('param.dateend');
            $dateatart = $this->request->post("dateatart");
            $dateend = $this->request->post("dateend");

            if ($dateatart && $dateend) {
                $condition["create_time"] = ['between', [$dateatart . " 00:00:00", $dateend . " 23:59:59"]];
            } else {
                if ($dateatart) {
                    $condition["create_time"] = ['>=', $dateatart . " 00:00:00"];
                }
                if ($dateend) {
                    $condition["create_time"] = ['<=', $dateend . " 23:59:59"];
                }
            }

            if ($typeId == 1) {
                // 淘宝导出
                $field = ['order_no', 'express_name', 'tracking_number'];
                //$query = db('order')->field($field)->where('order_state', 'in', [2, 3])->where('member_id', $memberid)->select();
                $query = db('order')->field($field)->where('order_state', 'in', [2, 3])->where($condition)->select();
            } else if ($typeId == 2) {
                // 京东导出
                $field = ['order_no', 'tracking_number'];
                //$query = db('order')->field($field)->where('order_state', 'in', [2, 3])->where('member_id', $memberid)->select();
                $query = db('order')->field($field)->where('order_state', 'in', [2, 3])->where($condition)->select();
            } else if ($typeId == 3) {
                // 拼多多导出
                $field = ['order_no', 'express_name', 'tracking_number'];
                //$query = db('order')->field($field)->where('order_state', 'in', [2, 3])->where('member_id', $memberid)->select();
                $query = db('order')->field($field)->where('order_state', 'in', [2, 3])->where($condition)->select();
            } else if ($typeId == 4) {
                // 明细导出
                $field = ['order_no', 'tracking_number', 'merchant_order_no', 'consignee_name', 'shipping_address', 'express_name', 'create_time', 'order_pay', 'order_state', 'goodsTitle', 'consignee_phone'];
                //$query = db('order')->field($field)->where('order_state', 'in', [2, 3])->where('member_id', $memberid)->select();
                $query = db('order')->field($field)->where('order_state', 'in', [2, 3])->where($condition)->select();
            } else {
                ds_json_encode(10002, "获取订单失败");
            }

            if ($query) {
                ds_json_encode(10000, "获取订单成功", $query);
            } else {
                ds_json_encode(10001, "获取订单失败");
            }
        } else {
            ds_json_encode(10003, "获取订单失败");
        }
    }
}