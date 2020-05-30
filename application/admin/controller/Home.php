<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/1
 * Time: 20:18
 */

namespace app\admin\controller;


class Home extends AdminBaseController
{
    public function home() {
        $workordercount = db('workorder')->where('wo_state',1)->count();
        $membercount =db('member')->count();
        $warehousecount =db('warehouse')->count();
        $this->assign('workordercount',$workordercount);
        $this->assign('warehousecount',$warehousecount);
        $this->assign('membercount',$membercount);
        return $this->fetch();
    }
}