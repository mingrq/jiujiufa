<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/7
 * Time: 21:48
 */

namespace app\admin\controller;


class Advertising extends AdminBaseController
{
    public function index(){
        return $this->fetch('advertising');
    }

}