<?php
/**
 * Created by PhpStorm.
 * User: 002
 * Date: 2020/5/21
 * Time: 15:41
 */

namespace app\admin\controller;


class Rank extends AdminBaseController
{

    /**
     * 获取用户等级列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getranklist(){
       $query= db('rank')->field('rank_id,rank_name')->select();
       if ($query){
           ds_json_encode(10000,"获取等级列表成功",$query);
       }else{
           ds_json_encode(10001,"获取等级列表失败");
       }
    }
}