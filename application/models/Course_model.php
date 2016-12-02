<?php

/**
 * Class Course_model
 *
 * @author wangnan <wangnanphp@163.com>
 * @date   16-12-2 15:10
 */
class Course_model extends MY_Model
{
    /**
     * getAllCount 获取课程表总数
     *
     * @return int 总数
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-02 14:51:04
     */
    public function getAllCount()
    {
        return $this->db->from('epack')
            ->count_all_results();
    }

    /**
     * getPageData 获取分页数据
     *
     * @param int $page      第几页
     * @param int $page_size 分页大小
     * @return array
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-02 15:31:54
     */
    public function getPageData(int $page, int $page_size = 20)
    {
        // page limit offset
        $page = 0 >= $page ? 1 : $page;
        $limit = 0 >= $page_size ? 20 : $page_size;
        $offset = 0 > $page ? 0 : ($page - 1) * $page_size;
        // select
        $fields = 'id,name,grade_id,subject,date_created';
        return $this->db->select($fields)
            ->from('epack')
            ->limit($limit, $offset)
            ->order_by('id', 'DESC')
            ->get()
            ->result_array();
    }
}
