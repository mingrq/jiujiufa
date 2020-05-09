<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/1
 * Time: 21:17
 */

namespace app\admin\controller;
use think\Controller;

class Login extends Controller
{
    public function index() {
        return $this->fetch('login');
    }
}