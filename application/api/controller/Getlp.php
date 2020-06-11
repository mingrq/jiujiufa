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
        //$parama = json_decode($param, true);
        if (!empty($param) && !empty($param['sid']) && !empty($param['sign']) && !empty($param['username'])) {
            $sid = $param['sid'];
            $sign = $param['sign'];
            $username = $param['username'];
            if (parent::verify_ticket($sid, $sign, $username)) {
                // 签名成功 调用接口
                $url = 'http://www.681kb.com/API/GetLp';
                $ausername = db('config')->where('config_code', 'goods_api_acc')->value('config_value');
                $pwd = db('config')->where('config_code', 'goods_api_pw')->value('config_value');
                $asid = time() . rand(10000000, 99999999);
                $pwd16 = substr(md5($pwd), 8, 16);
                $asign = strtolower(md5($ausername . $pwd16 . $asid));

                $paramData = array(
                    'sid' => $asid,
                    'sign' => $asign,
                    'username' => $ausername
                );
                $json_str = request_post($url, $paramData);
                // $json = json_decode($json_str, true);
                // echo json_encode($json);
                echo $json_str;
            } else {
                // 失败
                $json = array(
                    'state' => "签名验证失败",
                    'param' => $param
                );
                echo json_encode($json);
            }
        } else {
            // ds_json_encode(10000, "", $param['sid']);
            $json = array(
                'state' => "002参数数据异常"
            );
            echo json_encode($json);
        }
        exit();
    }
}