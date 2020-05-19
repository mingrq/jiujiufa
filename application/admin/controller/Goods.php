<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/2
 * Time: 21:36
 */

namespace app\admin\controller;


class Goods extends AdminBaseController
{

    public function index()
    {
        return $this->fetch('goods');
    }

}