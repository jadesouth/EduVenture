<?php

/**
 * Class User
 *
 * @author wangnan <wangnanphp@163.com>
 * @date   2016-12-01 23:50:57
 */
class User extends MY_Controller
{
    /**
     * login 用户登录
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-01 23:54:41
     */
    public function login()
    {
        // 已经登陆,直接进内部页面
        if (! empty($this->session->home_login_user)) {
            redirect(base_url() . 'course');
        }
        if ('post' == $this->input->method()) {
            $this->load->library('form_validation');
            if (false === $this->form_validation->run()) {
                http_ajax_response(1, $this->form_validation->error_string());
                return;
            }

            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $this->load->model('user_model');
            $user = $this->user_model
                ->getUserByUserName($username, 'id,username,display_name,password_hash,password_salt');
            if (empty($user)) {
                http_ajax_response(2, '用户不存在');

                return;
            }
            $this->load->helper('security');
            if ($user['password_hash'] !== edu_user_password($password, $user['password_salt'])) {
                http_ajax_response(3, '登录密码错误');

                return;
            }

            http_ajax_response(0, '登录成功');

            // 写入文件Session
            $this->session->home_login_user = [
                'id'           => $user['id'],
                'username'     => $user['username'],
                'display_name' => $user['display_name'],
            ];
        } else {
            $this->load->view('home/user/login');
        }
    }

    /**
     * logout 用户登出
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-02 01:40:37
     */
    public function logout()
    {
        $this->session->unset_userdata('home_login_user');
        redirect(base_url() . 'user/login');
    }
}
