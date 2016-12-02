<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 后台分页配置
 */
$config = [
    'per_page' => 20,
    'num_links' => 5,
    'use_page_numbers' => true,
    'full_tag_open' => '<nav class="text-right"><ul class="pagination">',
    'full_tag_close' => '</ul></nav>',
    'first_link' => '首页',
    'last_link' => '尾页',
    'first_tag_open' => '<li>',
    'first_tag_close' => '</li>',
    'last_tag_open' => '<li>',
    'last_tag_close' => '<li>',
    'next_tag_open' => '<li>',
    'next_tag_close' => '</li>',
    'prev_tag_open' => '<li>',
    'prev_tag_close' => '</li>',
    'cur_tag_open' => '<li class="active"><span>',
    'cur_tag_close' => '<span class="sr-only">(current)</span></span></li>',
    'num_tag_open' => '<li>',
    'num_tag_close' => '</li>',
];