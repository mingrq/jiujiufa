<?php
/**
 * 注册
 * User: ming
 * Date: 2020/5/1
 * Time: 15:03
 */

namespace app\common\model;

use think\Model;

class Register extends Model
{
    /**
     * 注册
     */
    public function register_member($data = array())
    {
        if ($data) {
            $query = db('member')->insert($data);
            if ($query) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }
}