<?php

/**
 * Class Task_model 任务相关模型
 *
 * @author wangnan <wangnanphp@163.com>
 * @date   2016-12-03 17:32:05
 */
class Task_model extends MY_Model
{
    /**
     * getCount 根据课程ID获取任务总数
     *
     * @param int $course_id 课程ID
     *
     * @return int
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-04 13:14:51
     */
    public function getCount($course_id)
    {
        $course_id = (int)$course_id;
        if (0 >= $course_id) {
            return 0;
        }

        return $this->db->from('hotspot')
            ->where('epack_id', $course_id)
            ->count_all_results();
    }

    /**
     * getPageData 获取课程任务分页数据
     *
     * @param int $course_id 课程ID
     * @param int $page      第几页
     * @param int $page_size 分页大小
     *
     * @return array
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-02 15:31:54
     */
    public function getPageData($course_id, $page, $page_size = 20)
    {
        $course_id = (int)$course_id;
        if (0 >= $course_id) {
            return 0;
        }
        // page limit offset
        $page = 0 >= $page ? 1 : $page;
        $limit = 0 >= $page_size ? 20 : $page_size;
        $offset = 0 > $page ? 0 : ($page - 1) * $page_size;
        // select
        $fields = 'hotspot.id,epack_id,hotspot.name AS task_name,hotspot.date_create,epack.name AS course_name';

        return $this->db->select($fields)
            ->from('hotspot')
            ->where('epack_id', $course_id)
            ->join('epack', 'hotspot.epack_id = epack.id', 'left')
            ->limit($limit, $offset)
            ->order_by('epack.id', 'DESC')
            ->get()
            ->result_array();
    }

    /**
     * create 创建任务数据
     *
     * @param array $data
     *
     * @return int
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-04 20:26:24
     */
    public function create(array $data = [], $clean_up = true)
    {
        if (empty($data) || ! is_array($data)) {
            return 0;
        }

        return true === $this->db->insert('hotspot', $data) ? $this->db->insert_id() : 0;
    }

    /**
     * getCourse 根据ID查询任务信息
     *
     * @param int    $id
     * @param string $fields
     *
     * @return array|bool
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-04 22:12:22
     */
    public function getTask($id, $fields = 'id')
    {
        $id = (int)$id;
        if (0 >= $id) {
            return false;
        }

        return $this->db->select($fields)
            ->from('hotspot')
            ->where('id', $id)
            ->limit(1)
            ->get()
            ->row_array();
    }

    /**
     * modify 根据ID修改数据
     *
     * @param int   $id
     * @param array $data
     * @param bool  $clean_up
     *
     * @return bool
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-04 22:13:07
     */
    public function modify($id, array $data = [], $clean_up = true)
    {
        $id = (int)$id;
        if (0 >= $id) {
            return false;
        }

        return $this->db->where('id', $id)
            ->update('hotspot', $data);
    }

    /**
     * delById 根据ID删除数据
     *
     * @param $id
     *
     * @return bool|mixed
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-04 21:50:54
     */
    public function delById($id)
    {
        $id = (int)$id;
        if (0 >= $id) {
            return false;
        }

        return $this->db->where('id', $id)
            ->delete('hotspot');
    }
}
