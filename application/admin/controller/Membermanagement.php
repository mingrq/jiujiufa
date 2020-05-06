<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/6
 * Time: 23:04
 */

namespace app\admin\controller;


class Membermanagement extends AdminBaseController
{

    public function index(){
        $this->fetch('memberlist');
    }
}