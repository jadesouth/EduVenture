<?php

/**
 * Class Question_model 问题相关Model
 *
 * @author wangnan <wangnanphp@163.com>
 * @date   2016-12-05 10:15:11
 */
class Question_model extends MY_Model
{
    /**
     * getCount 获取问题列表总数
     *
     * @param int $task_id 任务ID
     * @return int 总数
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-05 10:26:20
     */
    public function getCount($task_id)
    {
        $task_id = (int)$task_id;
        if (0 >= $task_id) {
            return 0;
        }
        return $this->db->from('component')
            ->where('hotspot_id', $task_id)
            ->count_all_results();
    }

    /**
     * getPageData 获取分页数据
     *
     * @param int $task_id   任务ID
     * @param int $page      第几页
     * @param int $page_size 分页大小
     * @return array
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-05 10:26:25
     */
    public function getPageData($task_id, $page, $page_size = 20)
    {
        $task_id = (int)$task_id;
        if (0 >= $task_id) {
            return 0;
        }
        // page limit offset
        $page = 0 >= $page ? 1 : $page;
        $limit = 0 >= $page_size ? 20 : $page_size;
        $offset = 0 > $page ? 0 : ($page - 1) * $page_size;
        // select
        $fields = 'id,name,date_created';
        return $this->db->select($fields)
            ->from('component')
            ->where('hotspot_id', $task_id)
            ->limit($limit, $offset)
            ->order_by('id', 'DESC')
            ->get()
            ->result_array();
    }

    /**
     * create 创建问题数据
     *
     * @param array $data
     * @return int
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-05 10:26:30
     */
    public function create(array $data = [], $clean_up = true)
    {
        if (empty($data) || ! is_array($data)) {
            return 0;
        }

        return true === $this->db->insert('component', $data) ? $this->db->insert_id() : 0;
    }

    /**
     * modify 根据ID修改数据
     *
     * @param int   $id
     * @param array $data
     * @param bool  $clean_up
     * @return bool
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-05 10:27:55
     */
    public function modify($id, array $data = [], $clean_up = true)
    {
        $id = (int)$id;
        if (0 >= $id) {
            return false;
        }

        return $this->db->where('id', $id)
            ->update('component', $data);
    }

    /**
     * getCourse 根据ID查询问题信息
     *
     * @param int    $id
     * @param string $fields
     * @return array|bool
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-05 10:33:10
     */
    public function getQuestion($id, $fields = 'id')
    {
        $id = (int)$id;
        if (0 >= $id) {
            return false;
        }
        return $this->db->select($fields)
            ->from('component')
            ->where('id', $id)
            ->limit(1)
            ->get()
            ->row_array();
    }

    /**
     * delById 根据ID删除数据
     *
     * @param $id
     *
     * @return bool|mixed
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-02 21:59:01
     */
    public function delById($id)
    {
        $id = (int)$id;
        if (0 >= $id) {
            return false;
        }

        return $this->db->where('id', $id)
            ->delete('component');
    }
}
