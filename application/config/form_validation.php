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
    'error_prefix'  => '',
    'error_suffix'  => '',
    // user 用户相关
    'user/login'    => [ // 用户注册
        ['field' => 'username', 'label' => '登录名', 'rules' => 'trim|required'],
        ['field' => 'password', 'label' => '密码', 'rules' => 'trim|required'],
    ],
    // course 课程相关
    'course/create' => [ // 创建课程
        ['field' => 'name', 'label' => '课程名', 'rules' => 'trim|required'],
        ['field' => 'desc', 'label' => '课程描述', 'rules' => 'trim|required'],
        ['field' => 'share', 'label' => '课程共享', 'rules' => 'trim|required|in_list[0,1]'],
        ['field' => 'image', 'label' => '封面图', 'rules' => 'trim|required'],
        ['field' => 'lt-lng', 'label' => '左上角坐标', 'rules' => 'trim|required|decimal'],
        ['field' => 'lt-lat', 'label' => '左上角坐标', 'rules' => 'trim|required|decimal'],
        ['field' => 'rb-lat', 'label' => '左下角坐标', 'rules' => 'trim|required|decimal'],
        ['field' => 'rb-lng', 'label' => '左下角坐标', 'rules' => 'trim|required|decimal'],
        ['field' => 'grade', 'label' => '年级', 'rules' => 'trim|required'],
        ['field' => 'subject', 'label' => '年级', 'rules' => 'trim|required'],
    ],
    // course 课程相关
    'course/modify' => [ // 编辑课程
        ['field' => 'course', 'label' => '选择课程', 'rules' => 'trim|required'],
        ['field' => 'name', 'label' => '课程名', 'rules' => 'trim|required'],
        ['field' => 'desc', 'label' => '课程描述', 'rules' => 'trim|required'],
        ['field' => 'share', 'label' => '课程共享', 'rules' => 'trim|required|in_list[0,1]'],
        ['field' => 'image', 'label' => '封面图', 'rules' => 'trim|required'],
        ['field' => 'lt-lng', 'label' => '左上角坐标', 'rules' => 'trim|required|decimal'],
        ['field' => 'lt-lat', 'label' => '左上角坐标', 'rules' => 'trim|required|decimal'],
        ['field' => 'rb-lat', 'label' => '左下角坐标', 'rules' => 'trim|required|decimal'],
        ['field' => 'rb-lng', 'label' => '左下角坐标', 'rules' => 'trim|required|decimal'],
        ['field' => 'grade', 'label' => '年级', 'rules' => 'trim|required'],
        ['field' => 'subject', 'label' => '年级', 'rules' => 'trim|required'],
    ],
];
