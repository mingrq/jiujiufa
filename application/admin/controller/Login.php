<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/1
 * Time: 21:17
 */

namespace app\admin\controller;


class Login extends AdminBaseController
{
    public function login() {
        return $this->fetch();
    }
}