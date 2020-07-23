<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/7/23
 * Time: 21:03
 */

namespace app\admin\controller;


class Houseware extends AdminBaseController
{
    public function index()
    {
        return $this->fetch('house');
    }


    /**
     * 添加仓库内页
     */
    public function addhousecontent()
    {
        if (request()->isPost()) {
            $home_name = input('param.homename');
            $warehouse = input('param.warehouse');
            $kdname = input('param.kdname');
            $warehouse_arr = json_decode($warehouse);
            $cost = input('param.cost');

            $data = [];
            for ($h = 0; $h < count($warehouse_arr); $h++) {
                $warahouse_info = db('wasehouse_class')->where("class_id", $warehouse_arr[$h])->find();
                $homeTitle = $home_name . $warahouse_info["class_name"];
                $x = ["wh_title" => $homeTitle, "wh_class" => $warehouse_arr[$h], "wh_alias" => $kdname, "wh_kd_cost" => $cost];
                $data[] = $x;
            }
            $query = db('warehouse')->insertAll($data);
            if ($query > 0) {
                ds_json_encode(10000, "添加仓库成功");
            } else {
                ds_json_encode(10001, "添加仓库失败");
            }
        } else {
            return $this->fetch('addhousecontent');
        }
    }

    /**
     * 获取仓库信息列表
     */
    public function getwarehouselist()
    {
        $model = model('warehouse');
        $warehouses = $model->getWarehouselistg();
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
     * 搜索仓库
     */
    public function serachhouselist()
    {
        $condition = array();
        $warehouseclass = input('param.warehouseclass');
        if ($warehouseclass)
            $condition["wh_class"] = $warehouseclass;

        $query = db('v_warehouse')
            ->where($condition)
            ->order('wh_id desc')
            ->paginate(100);
        if ($query) {
            ds_json_encode(10000, "搜索仓库成功", $query);
        } else {
            ds_json_encode(10001, "搜索仓库失败");
        }
    }


    /**
     * 删除仓库
     */
    public function delhouseware()
    {
        if (request()->isPost()){
            $wh_id = input('param.wh_id');
            db('warehouse')->where("wh_id",$wh_id)->delete();
            ds_json_encode(10000, "删除仓库成功");
        }else{
            ds_json_encode(10001, "请求错误");
        }
    }



    /**
     * 设置
     */
    public function edithouse()
    {
        $field = input('param.field');
        $wh_id = input('param.wh_id');
        $price = input('param.price');
        $qurey = db('warehouse')->where('wh_id', $wh_id)->setField($field, $price);
        if ($qurey) {
            ds_json_encode(10000, "修改成功");
        }
        ds_json_encode(10001, "修改失败");
    }
}