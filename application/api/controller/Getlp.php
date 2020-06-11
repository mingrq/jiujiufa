<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/30
 * Time: 22:57
 */

namespace app\api\controller;

/**
 *  获取小礼品价格接口
 */
class Getlp extends ApiBaseController
{
    public function getLp()
    {
        $param = $this->request->post();
        $parama = json_decode($param, true);
        ds_json_encode(10000, "", $parama['sid']);
    }
}