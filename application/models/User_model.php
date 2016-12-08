<?php

/**
 * Class User_model 用户相关模型
 *
 * @author wangnan <wangnanphp@163.com>
 * @date   2016-12-02 00:07:17
 */
class User_model extends MY_Model
{
    /**
     * User_model constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->_table = 'ee3_user';
    }

    /**
     * getUserByUserName  根据用户名查询用户
     *
     * @param string $username 用户名
     * @param string $fields
     *
     * @return array|bool
     *
     * @author wangnan <wangnanphp@163.com>
     * @date 2016-12-02 01:43:06
     */
    public function getUserByUserName($username, $fields = 'id')
    {
        if(empty($username)) {
            return false;
        }
        $fields = empty($fields) ? 'id' : $fields;
        return $this->db->select($fields)
            ->from($this->_table)
            ->where('username', $username)
            ->limit(1)
            ->get()
            ->row_array();
    }
}
