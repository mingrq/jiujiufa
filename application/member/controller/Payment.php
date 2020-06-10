<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/22
 * Time: 23:52
 */

/**
 * 支付回调
 */

namespace app\member\controller;

use app\common\model\Member;
use think\Controller;

class Payment extends Controller
{
    /**
     * 异步通知地址
     */
    public function alipaynotify()
    {
        $alipaymode = model('alipaymodel');
        $notify_result = $alipaymode->verify_notify();
        if ($notify_result) {
            if ($notify_result['trade_status'] == 1) {
                //支付成功
                $out_trade_no = $notify_result['out_trade_no']; #商户订单号
                $trade_no = $notify_result['trade_no']; #交易凭据单号
                $total_fee = $notify_result['total_fee']; #涉及金额
                $order_type = $notify_result['order_type'];
                $time = date('Y-m-d H:i:s', time());
                $query = db('recharge_record')->where('recharge_trade_no', $out_trade_no)->update(['trade_no' => $trade_no, 'trade_body' => $order_type, 'recharge_state' => 2, 'recharge_time' => $time]);
                if ($query) {
                    //获取用户信息，添加用户余额
                    $member = db('recharge_record')->where('recharge_trade_no', $out_trade_no)->find();
                    $memberid = $member['recharge_member_id'];
                    db('member')->where('member_id', $memberid)->setInc('member_balance', $total_fee);
                    //判断是否需要升级
                    $recharge_money = db('recharge_record')->where('recharge_member_id', $memberid)->sum('recharge_money');
                    $rankid = db('member')->where('member_id', $memberid)->value('member_rank');
                    $ranklist = db('rank')->where('rank_id', '>', $rankid)->select();
                    for ($i = 0; $i < count($ranklist); $i++) {
                        $rank = $ranklist[$i];
                        if ($recharge_money > $rank['recharge_upgrade']) {
                            db('member')->where('member_id', $memberid)->update(['member_rank' => $rank['rank_id']]);
                        }
                    }
                    // 您推荐的用户完成注册后， 并且累计充值金额满99金币， 即可奖励您10金币！
                    $config = db("config")->where("config_code", "=", "recharge_cashback")->find();
                    if (!empty($config)) {
                        $config_value_arr = explode('&', $config['config_value']);
                        if (count($config_value_arr) == 2) {
                            // 查询充值人的充值金额
                            if (($recharge_money - $total_fee) < $config_value_arr[0] && $recharge_money > $config_value_arr[0]) {
                                // 充值前 小于 满多少  充值后 大于 满多少
                                $czMember = Member::get($memberid);
                                if (!empty($czMember) && !empty($czMember['member_referrer'])) {
                                    $result = db("member")->where('member_id', '=', $czMember['member_referrer'])->setInc('member_balance', $config_value_arr[1]);
                                    if ($result) {
                                        //记录资金明细
                                        db('moneychange_record')->insert(['member_id' => $czMember['member_referrer'], 'change_money' => $config_value_arr[1], 'change_cause' => '推荐的用户累计充值金额满' . $config_value_arr[0] . '金币，奖励' . $config_value_arr[1] . '金币']);
                                    }
                                }
                            }
                        }
                    }
                    //记录资金明细
                    db('moneychange_record')->insert(['member_id' => $memberid, 'change_money' => $total_fee, 'change_cause' => $order_type]);
                }
            }
        }
    }

    /**
     * 跳转页面
     */
    public function alipayreturn()
    {
        $alipaymode = model('alipaymodel');
        $notify_result = $alipaymode->return_verify();

        if ($notify_result['trade_status'] == 1) {
            //支付成功
            // $this->assign('tes', json_encode($notify_result));
            // return $this->fetch();
            return $this->redirect("/member/finance/rechargerecord", ['code' => 200]);
        } else {
            //支付失败
            return $this->redirect("/member/finance/rechargerecord", ['code' => 404]);
        }
    }
}