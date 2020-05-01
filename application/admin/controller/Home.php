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
        return $this->fetch();
    }
}