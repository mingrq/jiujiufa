<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/6
 * Time: 22:51
 */

namespace app\admin\controller;


class Guestbook extends  AdminBaseController
{

    public function index(){
        return $this->fetch('guestbook');
    }
}