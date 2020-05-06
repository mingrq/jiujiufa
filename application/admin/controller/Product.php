<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/2
 * Time: 21:36
 */

namespace app\admin\controller;


class Product extends AdminBaseController
{

    public function productslist()
    {
        return $this->fetch();
    }

}