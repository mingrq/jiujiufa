<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/16
 * Time: 9:00
 */

namespace app\common\model;


use think\Model;

class Member extends Model
{
    /**
     * 会员详细信息（查库）
     * @access public
     * @author csdeshang
     * @param array $condition 条件
     * @param string $field 字段
     * @return array
     */
    public function getMemberInfo($condition,$field='*')
    {
        $res = db('member')->field($field)->where($condition)->find();
        return $res;
    }
}