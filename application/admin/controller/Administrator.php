<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/6
 * Time: 22:03
 */

namespace app\admin\controller;


class Administrator extends AdminBaseController
{

    public function index(){
        return $this->fetch('admin_info');
    }

}