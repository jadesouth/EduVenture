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
    // course 任务相关
    'task/create' => [ // 创建任务
        ['field' => 'task-course', 'label' => '课程', 'rules' => 'trim|required'],
        ['field' => 'name', 'label' => '任务标题', 'rules' => 'trim|required'],
        ['field' => 'task-image', 'label' => '任务封面图', 'rules' => 'trim|required'],
        ['field' => 'task-center-lng', 'label' => '任务区域', 'rules' => 'trim|required|decimal'],
    ],
    'task/modify' => [ // 编辑任务
        ['field' => 'edit-course-task', 'label' => '任务', 'rules' => 'trim|required'],
        ['field' => 'edit-task-name', 'label' => '任务标题', 'rules' => 'trim|required'],
        ['field' => 'edit-task-image', 'label' => '任务封面图', 'rules' => 'trim|required'],
        ['field' => 'task-center-lng', 'label' => '任务区域', 'rules' => 'trim|required|decimal'],
    ],
    // question 题目相关
    'question/createSingleSelect' => [ // 添加单选
        ['field' => 'name', 'label' => '问题标题', 'rules' => 'trim|required'],
        ['field' => 'right', 'label' => '正答', 'rules' => 'trim|required|is_natural_no_zero'],
        ['field' => 'question-task', 'label' => '任务', 'rules' => 'trim|required|is_natural_no_zero'],
        ['field' => 'answer[]', 'label' => '题目内容', 'rules' => 'trim|required'],
    ],
];
