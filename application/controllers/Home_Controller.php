<?php

/**
 * Class Home_Controller
 *
 * @author wangnan <wangnanphp@163.com>
 * @date   2016-12-01 13:06:38
 */
class Home_Controller extends MY_Controller
{
    /**
     * @var array 登录用户信息
     */
    protected $_loginUser = [];


    /**
     * Home_Controller constructor.
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-02 22:21:03
     */
    public function __construct()
    {
        parent::__construct();

        // 检测登陆
        if (empty($this->session->home_login_user)) {
            redirect('user/login');
        }
        // 赋值登陆信息
        $this->_loginUser = $this->session->home_login_user;
    }
}
