<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/7
 * Time: 20:16
 */

namespace app\admin\controller;


class Article extends AdminBaseController
{

    public function index(){
        return $this->fetch('article_list');
    }

}