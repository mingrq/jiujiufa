<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/6
 * Time: 22:51
 */

namespace app\admin\controller;


class Workorder extends AdminBaseController
{

    public function index()
    {
        return $this->fetch('workorder');
    }

    /**
     * 获取工单列表
     */
    public function getorderworklist(){
        $model=model('workorder');
        $query=$model->getWorkOrderlist();
        if ($query){
            ds_json_encode(10000, "获取工单列表成功", $query);
        }else{
            ds_json_encode(10001, "获取工单列表失败");
        }
    }





    public function batchadd()
    {
        for ($i = 100; $i < 200; $i++) {
            $data=['wo_title'=>'工单标题'.$i,'wo_content'=>'工单内容工单内容工单内容工单内容工单内容工单内容工单内容工单内容工单内容工单内容工单内容工单内容工单内容工单内容工单内容工单内容工单内容工单内容'.$i,'wo_linkman'=>'测试人','wo_phone'=>'16630821566'];
            db('workorder')->insert($data);
        }
    }
}