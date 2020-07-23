<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/22
 * Time: 21:38
 */

/**
 * 仓库
 */

namespace app\common\model;


use think\Model;

class Warehouse extends Model
{

    protected $pk = 'wh_id';

    /**
     * 获取仓库信息列表
     */
    public function getWarehouselist()
    {
        return db('warehouse')->select();
    }
    public function getWarehouselistg($condition = array(), $field = '*', $page = 100, $order = 'wh_id desc')
    {
        return db('v_warehouse')->field($field)->where($condition)->order($order)->paginate($page, false, ['query' => request()->param()]);
    }
    /**
     * 获取仓库信息列表
     */
    public function getWarehouseclasslist()
    {
        return db('wasehouse_class')->select();
    }



}