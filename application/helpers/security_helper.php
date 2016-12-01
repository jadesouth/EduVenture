<?php

/**
 * Security_helper.php 安全函数帮助文件
 *
 * @author wangnan <wangnanphp@163.com>
 * @date   16-8-16 20:05
 */

if (! function_exists('generate_home_password')) {
    /**
     * generate_home_password 生成前台密码
     *
     * @param string $password 原始密码
     * @param string $salt     加密盐值
     *
     * @return string 生成的密码字符串
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-11-13 20:28:26
     */
    function generate_home_password(string $password, string $salt): string
    {
        if (empty($password) || empty($salt)) {
            return false;
        }

        return md5(md5($password) . md5($salt));
    }
}

if (! function_exists('generate_admin_password')) {
    /**
     * generate_admin_password 生成后台密码
     *
     * @param string $password 原始密码
     * @param string $salt     加密盐值
     *
     * @return string 生成的密码字符串
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-08-16 20:08:51
     */
    function generate_admin_password(string $password, string $salt): string
    {
        if (empty($password) || empty($salt)) {
            return false;
        }

        return md5(md5($password) . $salt);
    }
}

if (! function_exists('edu_user_password')) {
    /**
     * edu_user_password 生成EDU项目密码
     *
     * @param string $password 原始密码
     * @param string $salt     加密盐值
     *
     * @return string 生成的密码字符串
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-02 00:24:23
     */
    function edu_user_password($password, $salt)
    {
        return crypt($password, $salt);
    }
}
