<?php
/**
 * 小礼品相关接口
 */

namespace app\api\controller;

use app\common\controller\ApiBase;

class Apigift extends ApiBase
{
    private $username = '';

    protected function _initialize()
    {
        parent::_initialize();
        $this->username = trim($this->request->post("username"));
    }

    public function index()
    {
        return '1231321313000011';
    }

    /**
     * 获取小礼品
     */
    public function giftList()
    {
        $member = db("member")->field('member_id')->where('member_login_name', '=', $this->username)->find();
        /*$whereGoods['vgoods.good_state'] = 1;

        $specialpricelist = db('special_price')->where('member_id', $member['member_id'])->fetchSql(true)->select();
        $query = db('v_goods')->alias('vgoods')->join('(' . $specialpricelist . ') `specialprice`', 'specialprice.kd_id = vgoods.kdId', 'LEFT')->where($whereGoods)->field(['vgoods.*', 'specialprice.good_special_api_price'])->order("vgoods.kdId asc")->fetchSql(true)->select();
        */
        // $member = db("member")->where('member_login_name', '=', "18733768375")->fetchSql(true)->find();
        //db("member")->where("member_login_name", "=", "18733768375")->find();
        //return "1234560000";


        /*
        $whereGoods['vgoods.good_state'] = 1;

        $specialpricelist = db('special_price')->where('member_id', $member['member_id'])->fetchSql(true)->select();
        $query = db('v_goods')->alias('vgoods')->join('(' . $specialpricelist . ') `specialprice`', 'specialprice.kd_id = vgoods.kdId', 'LEFT')->where($whereGoods)->field(['vgoods.*', 'specialprice.good_special_api_price'])->order("vgoods.kdId asc")->fetchSql(true)->select();
        return $query;
        */


        $whereGoods['vgoods.good_state'] = 1;

        $specialpricelist = db('special_price')->where('member_id', 3)->fetchSql(true)->select();
        $query = db('v_goods')->alias('vgoods')->join('(' . $specialpricelist . ') `specialprice`', '`specialprice`.`kd_id` = `vgoods`.`kdId`', 'LEFT')->where($whereGoods)->field(['`vgoods`.*', '`specialprice`.`good_special_api_price`'])->fetchSql(true)->select();
        return $query;
    }
}