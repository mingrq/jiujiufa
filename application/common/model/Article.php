<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/26
 * Time: 22:17
 */

namespace app\common\model;


use think\Model;

class Article extends Model
{

    /**
     * 获取文章列表
     */
    public function articlelist($condition = array(), $field = '*', $page = 100, $order = 'article_id desc')
    {
        if ($page) {
            $member_list = db('v_member')->field($field)->where($condition)->order($order)->paginate($page, false, ['query' => request()->param()]);
            return $member_list;
        } else {
            return db('v_member')->field($field)->where($condition)->order($order)->select();
        }
    }
}