<?php

namespace app\common\validate;

use think\Validate;

class Member extends Validate
{
    /*
    protected $rule = [
        'member_login_name' => 'require|max:32|min|5',
        'member_login_pw' => 'require|max:32|min|6'
    ];
    */
    protected $rule = [
        ['member_login_name', 'require|max:32|min:6', '账号不能为空|账号最多不能超过32个字符|账号最少不能少于5个字符'],
        ['member_login_pw', 'require|max:32|min:6', '密码不能为空|密码最多不能超过32个字符|密码最少不能少于5个字符'],
        ['member_mobile', ['regex'=>'/^1(3|4|5|6|7|8|9)\d{9}$/'], '请输入有效的手机号码'],
        ['member_qq', 'require|number', 'QQ号不能为空|QQ号格式错误']
    ];
    // 验证场景
    protected $scene = [
        'register' => ['member_login_name', 'member_login_pw', 'member_mobile'],
        'login' => ['member_login_name', 'member_login_pw'],
        'findpwd' => ['member_login_pw'],
        'edit_pwd' =>['member_login_pw','member_qq'],
        'edit' =>['member_qq']
    ];
}
