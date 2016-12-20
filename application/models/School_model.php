<?php

/**
 * Class school_model 学校相关模型
 * 
 * @author wangnan <wangnanphp@163.com>
 * @date   16-12-20 10:06
 */
class school_model extends MY_Model
{
    /**
     * readSchoolPair 获取学校的ID/名称对
     *
     * @return array ['id' => 'name', ...]
     *
     * @author wangnan <wangnanphp@163.com>
     * @date 2016-12-20 09:47:28
     */
    public function readSchoolPair()
    {
        $school_arr = $this->db->select('id,name')
            ->from($this->_table)
            ->get()
            ->result_array();

        empty($school_arr) || $school_arr = array_column($school_arr, 'name', 'id');
        return $school_arr;
    }
}
