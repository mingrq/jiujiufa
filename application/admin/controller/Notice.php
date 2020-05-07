<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/7
 * Time: 20:20
 */

namespace app\admin\controller;


class Notice extends AdminBaseController
{

    public function index(){
        return $this->fetch('notice');
    }
}