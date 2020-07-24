<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/2
 * Time: 21:36
 */

namespace app\admin\controller;

use think\Db;

class Goods extends AdminBaseController
{

    public function index()
    {
        $goods_mem_defult_price = db('config')->where('config_id', '6')->value('config_value');
        $goods_vip_defult_price = db('config')->where('config_id', '7')->value('config_value');
        $goods_api_defult_price = db('config')->where('config_id', '8')->value('config_value');
        $this->assign('goods_mem_defult_price', $goods_mem_defult_price);
        $this->assign('goods_vip_defult_price', $goods_vip_defult_price);
        $this->assign('goods_api_defult_price', $goods_api_defult_price);
        return $this->fetch('goods');
    }


    /**
     * 添加商品内页
     */
    public function addgoodscontent()
    {
        if (request()->isPost()) {
            $goods_id = input('param.goods_id');
            $goods_name = input('param.goods_name');
            $warehouse = input('param.warehouse');
            $warehouse_arr = json_decode($warehouse);
            $file = request()->file('good_pic');
            $good_pic = "";
            if ($file) {
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->move(ROOT_PATH . 'public' . DS . 'goods');
                //如果不清楚文件上传的具体键名，可以直接打印$info来查看
                //获取文件（日期/文件名），$info->getSaveName()  **********不同之处，笔记笔记哦
                $ad_pic = $info->getSaveName();  //在测试的时候也可以直接打印文件名称来查看
                $good_pic = DS . 'goods' . DS . $ad_pic;
            }

            $cost = input('param.cost');
            $api_profit = input('param.api_profit');
            $agency_profit = input('param.agency_profit');
            $member_profit = input('param.member_profit');

            $data = [];
            for ($h=0;$h<count($warehouse_arr);$h++){
                $warahouse_info = db('warehouse')->where("wh_id", $warehouse_arr[$h])->find();
                $goodsTitle =$warahouse_info["wh_title"] . "+" . $goods_name;
                $cost=$cost+$warahouse_info["wh_kd_cost"];
                $x = ["good_id" => $goods_id, "goodsTitle" => $goodsTitle, "classId" => $warehouse_arr[$h], "kdName" => $goodsTitle, "cost_price" => $cost, "good_price" => $member_profit, "good_vip_price" => $agency_profit, "good_api_price" => $api_profit, "good_pic" => $good_pic];
                $data[]=$x;
            }
            $query = db('goods')->insertAll($data);
            if ($query>0) {
                ds_json_encode(10000, "商品添加成功");
            } else {
                ds_json_encode(10001, "商品添加失败");
            }
        } else {
            return $this->fetch('addgoodscontent');
        }
    }

    /**
     * 获取商品列表
     */
    public function getgoodslist()
    {
        $mode = model('goods');
        $condition = array("good_state" => 1);
        $query = $mode->getGoodsList($condition);
        if ($query) {
            ds_json_encode(10000, "获取商品列表成功", $query);
        } else {
            ds_json_encode(10001, "获取商品列表失败");
        }
    }

    /**
     * 删除商品
     */
    public function delgood()
    {
        if (request()->isPost()){
            $kdId = input('param.kdId');
            db('goods')->where("kdId",$kdId)->delete();
            db('special_price')->where("kd_id",$kdId)->delete();
            ds_json_encode(10000, "删除商品成功");
        }else{
            ds_json_encode(10001, "请求错误");
        }

    }


    /**
     * 编辑
     */
    public function editprice()
    {
        $field = input('param.field');
        $kdId = input('param.kdId');
        $price = input('param.price');
        if ($field=='goodsTitle'){
            $qurey = db('goods')->where('kdId', $kdId)->update([$field => $price, 'kdName' => $price]);
        }else{
            $qurey = db('goods')->where('kdId', $kdId)->setField($field, $price);
        }
        if ($qurey) {
            ds_json_encode(10000, "修改价格成功");
        }
        ds_json_encode(10001, "修改价格失败");
    }

    /**
     * 编辑图片
     */
    public function editpic()
    {
        $kdId = input('param.kdId');
        $file = request()->file('good_pic');
        $good_pic = "";
        if ($file) {
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'goods');
            //如果不清楚文件上传的具体键名，可以直接打印$info来查看
            //获取文件（日期/文件名），$info->getSaveName()  **********不同之处，笔记笔记哦
            $ad_pic = $info->getSaveName();  //在测试的时候也可以直接打印文件名称来查看
            $good_pic = DS . 'goods' . DS . $ad_pic;
        }else{
            ds_json_encode(10001, "图片为空");
        }
        $query = db('goods')->where('kdId', $kdId)->update(["good_pic" => $good_pic]);
        if ($query>0) {
            ds_json_encode(10000, "图片修改成功",$good_pic);
        } else {
            ds_json_encode(10001, "图片修改失败");
        }
    }

    /**
     * 批量设置价格
     */

    public function batcheditprice()
    {
        $good_price = input('param.good_price');
        $good_vip_price = input('param.good_vip_price');
        $good_api_price = input('param.good_api_price');
        db('config')->update(['config_value' => $good_price, 'config_id' => 6]);
        db('config')->update(['config_value' => $good_vip_price, 'config_id' => 7]);
        db('config')->update(['config_value' => $good_api_price, 'config_id' => 8]);
        $qurey = db('goods')->where('kdId', '>', '0')->update(['good_price' => $good_price, 'good_vip_price' => $good_vip_price, 'good_api_price' => $good_api_price]);
        if ($qurey) {
            ds_json_encode(10000, "修改价格成功");
        }
        ds_json_encode(10001, "修改价格失败");
    }

    /**
     * 搜索礼品
     */
    public function serachgoodslist()
    {
        $condition = array();
        $goodtitle = input('param.goodtitle');
        $find = array('\\', '/', '%', '_', '&');
        $replace = array('\\\\', '\\/', '\%', '\_', '\&');
        $goodtitle = '%' . trim(str_replace($find, $replace, $goodtitle)) . '%';
        if ($goodtitle && trim($goodtitle) != "") {
            $condition['goodsTitle'] = ['like', $goodtitle];
        }

        $warehouse = input('param.warehouse');
        if ($warehouse) {
            $condition["classId"] = $warehouse;
        }

        $query = db('v_goods')
            ->where($condition)
            ->order('kdId desc')
            ->paginate(100);
        if ($query) {
            ds_json_encode(10000, "搜索商品成功", $query);
        } else {
            ds_json_encode(10001, "搜索商品失败");
        }
    }


    /**
     * 获取仓库信息列表
     */
    public function getwarehouselist()
    {
        $model = model('warehouse');
        $warehouses = $model->getWarehouselist();
        if ($warehouses) {
            ds_json_encode(10000, "获取仓库列表成功", $warehouses);
        } else {
            ds_json_encode(10001, "获取仓库列表失败");
        }
    }

    /**
     * 获取仓库信息列表
     */
    public function getwarehouseclasslist()
    {
        $model = model('warehouse');
        $warehouseclasss = $model->getWarehouseclasslist();
        if ($warehouseclasss) {
            ds_json_encode(10000, "获取仓库类型列表成功", $warehouseclasss);
        } else {
            ds_json_encode(10001, "获取仓库类型列表失败");
        }
    }

    /**
     * 更新商品列表
     */
    public function goodsupdate()
    {
        $mod = model('goodsupdate');
        $mod->goods_update();
    }

    /**
     * 获取默认价格
     */
    public function getdefultprice()
    {
        return db('config')->where('config_id', 'in', [6, 7, 8])->select();
    }
}