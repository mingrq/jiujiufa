<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/7
 * Time: 21:27
 */

namespace app\admin\controller;


class Orderform extends AdminBaseController
{
public function index(){
    return $this->fetch('orderform');

}
}