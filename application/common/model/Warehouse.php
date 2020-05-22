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

    /**
     * 获取仓库信息列表
     */
    public function getWarehouselist(){
        return db('warehouse')->select();
    }
}