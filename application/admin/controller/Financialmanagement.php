<?php
/**
 * Created by PhpStorm.
 * User: 002
 * Date: 2020/6/5
 * Time: 10:19
 */

/**
 * 财务管理
 */

namespace app\admin\controller;


class Financialmanagement extends AdminBaseController
{
    /**
     * 充值记录
     */
    public function rechargerecord()
    {
        if (request()->isPost()){
            $condition = array();
            $condition['recharge_state'] = 2;
            $member_mode = model('member');
            $query = $member_mode->getRechargeRecord($condition);
            if ($query) {
                ds_json_encode(10000, "获取充值记录成功", $query);
            } else {
                ds_json_encode(10001, "获取充值记录失败");
            }
        }else{
            return $this->fetch();
        }
    }

    /**
     * 搜索充值记录
     */
    public function serachrechargerecord()
    {
        $condition = array();
        $condition['recharge_state'] = 2;

        $serach_param = input('param.serach_param');
        $find = array('\\', '/', '%', '_', '&');
        $replace = array('\\\\', '\\/', '\%', '\_', '\&');
        $serach_param = '%' . trim(str_replace($find, $replace, $serach_param)) . '%';
        if ($serach_param && trim($serach_param) != "") {
            $condition['member_mobile|member_qq|recharge_trade_no'] = ['like', $serach_param];
        }

        $dateatart = input('param.dateatart');
        $dateend = input('param.dateend');

        if ($dateatart && $dateend) {
            $condition["recharge_time"] = ['between', [$dateatart, $dateend]];
        } else {
            if ($dateatart) {
                $condition["recharge_time"] = ['>=', $dateatart];
            }

            if ($dateend) {
                $condition["recharge_time"] = ['<=', $dateend];
            }
        }

        $query = db('v_recharge_record')
            ->where($condition)
            ->order('recharge_time desc')
            ->paginate(100);
        if ($query) {
            ds_json_encode(10000, "搜索充值记录成功", $query);
        } else {
            ds_json_encode(10001, "搜索充值记录失败");
        }
    }

}