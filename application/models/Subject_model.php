<?php

/**
 * Class Subject_model
 *
 * @author wangnan <wangnanphp@163.com>
 * @date   2016-12-02 22:35:07
 */
class Subject_model extends MY_Model
{
    /**
     * getAll 获取所有学科
     *
     * @return array
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-02 22:36:41
     */
    public function getAll()
    {
        return $this->db->select('id,name')
            ->from('subject')
            ->get()
            ->result_array();
    }
}