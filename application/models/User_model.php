<?php

/**
 * Class User_model
 *
 * @author wangnan <wangnanphp@163.com>
 * @date   2016-12-02 00:07:17
 */
class User_model extends MY_Model
{
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
    public function getUserByUserName(string $username, string $fields = 'id')
    {
        if(empty($username)) {
            return false;
        }
        $fields = empty($fields) ? 'id' : $fields;
        return $this->db->select($fields)
            ->from('user')
            ->where('username', $username)
            ->limit(1)
            ->get()
            ->row_array();
    }
}
