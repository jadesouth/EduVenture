<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 表单验证匹配规则配置文件
 *
 * @author wangnan <wangnanphp@163.com>
 * @date   2016-12-02 01:42:34
 */
$config = [
    // 配置错误定界符
    'error_prefix' => '',
    'error_suffix' => '',
    'user/login' => [ // 用户注册
        ['field' => 'username', 'label' => '登录名', 'rules'=> 'trim|required'],
        ['field' => 'password', 'label' => '密码', 'rules'=> 'trim|required'],
    ],
];
