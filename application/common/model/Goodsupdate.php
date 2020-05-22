<?php
/**
 * Created by PhpStorm.
 * User: 002
 * Date: 2020/5/22
 * Time: 11:15
 */

namespace app\common\model;


class Goodsupdate
{
    /**
     * 商品更新
     */
    public function goods_update()
    {
        $url = 'http://www.681kb.com/API/GetLp';
        $username = db('config')->where('config_code', 'goods_api_acc')->value('config_value');
        $pwd = db('config')->where('config_code', 'goods_api_pw')->value('config_value');
        $sid = time() . '123456369876';
        $pwd16 = substr(md5($pwd), 8, 16);
        $sign = strtolower(md5($username . $pwd16 . $sid));
        $data = array(
            'sid' => $sid,
            'sign' => $sign,
            'username' => $username
        );

        $json_str = request_post($url, $data);
        $json = json_decode($json_str, true);
        $good_arr = $json['lpPrice'];
        $good_kdid_arr = array();//在售商品id集合
        for ($i = 0; $i < count($good_arr); $i++) {

            $good = $good_arr[$i];
            $kdId = $good['kdId'];//快递id
            $good_kdid_arr[] = $kdId;

            $pid = $good['pid'];//商品id
            $cost_price = $good['apiPrice'];//成本
            $kdName = $good['kdName'];//快递名称
            $tips = $good['tips'];//简介
            $goodsTitle = $good['goodsTitle'];//商品标题
            $good_pic = $good['pic'];//商品主图
            $classId = $good['classId'];//仓库id
            $param = [];
            if ($pid) {
                $param['good_id'] = $pid;
            }
            if ($cost_price) {
                $param ["cost_price"] = $cost_price;
            }
            if ($kdName) {
                $param ["kdName"] = $kdName;
            }
            if ($tips) {
                $param ["tips"] = $tips;
            }
            if ($goodsTitle) {
                $param ["goodsTitle"] = $goodsTitle;
            }
            if ($good_pic) {
                $param ["good_pic"] = $good_pic;
            }
            if ($classId) {
                $param ["classId"] = $classId;
            }
            $param ["good_state"] = 1;

            $good = db('goods')->where('kdId', $kdId)->find();
            if ($good) {
                //商品存在
               db('goods')->where('kdId', $kdId)->update($param);
            } else {
                //商品不存在
                $param ["kdId"] = $kdId;
                db('goods')->insert($param);
            }
        }
        db('goods')->where('kdId', 'not in', $good_kdid_arr)->update(['good_state' => 2]);
        ds_json_encode(10000,"商品更新成功");
    }
}