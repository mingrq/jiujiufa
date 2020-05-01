<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/1
 * Time: 20:18
 */

namespace app\admin\controller;


class Index extends AdminBaseController
{
    public function index() {
        return $this->fetch();
    }
}