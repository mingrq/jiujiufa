<?php
/**
 * 登陆
 * User: ming
 * Date: 2020/5/1
 * Time: 13:17
 */

namespace app\model;
use think\Model;


class Login extends Model
{

    /**
     * 会员详细信息（查库）
     * @access public
     * @author csdeshang
     * @param array $condition 条件
     * @param string $field 字段
     * @return array
     */
    public function getMemberInfo($condition, $field = '*')
    {
        $res = db('member')->field($field)->where($condition)->find();
        return $res;
    }


    /**
     * 取得会员详细信息（优先查询缓存）
     * 如果未找到，则缓存所有字段
     * @access public
     * @author csdeshang
     * @param int $member_id 会员ID
     * @return array
     */
    public function getMemberInfoByID($member_id)
    {
        $member_info = rcache($member_id, 'member');
        if (empty($member_info)) {
            $member_info = $this->getMemberInfo(array('member_id' => $member_id), '*');
            wcache($member_id, $member_info, 'member');
        }
        return $member_info;
    }


}