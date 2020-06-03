<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/27
 * Time: 13:28
 */

namespace app\common\model;


use think\Model;

class Notice extends Model
{
    /**
     * 获取公告列表
     */
    public function noticelist($condition = array(), $field = '*', $page = 100, $order = 'notice_id desc')
    {
        if ($page) {
            $member_list = db('notice')->field($field)->where($condition)->order($order)->paginate($page, false, ['query' => request()->param()]);
            return $member_list;
        } else {
            return db('notice')->field($field)->where($condition)->order($order)->select();
        }
    }
}