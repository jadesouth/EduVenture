<?php

/**
 * Class File_model
 *
 * @author wangnan <wangnanphp@163.com>
 * @date   2016-12-02 23:49:11
 */
class File_model extends MY_Model
{
    /**
     * createCourseImage 创建课程封面图片
     *
     * @param $path
     * @param $file_name
     * @param $ext
     * @param $display_name
     * @param $file_size
     *
     * @return bool
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-02 23:54:34
     */
    public function createCourseImage($path, $file_name, $ext, $display_name, $file_size)
    {
        $insert_data = [
            'path'         => $path,
            'filename'    => $file_name,
            'extension'    => $ext,
            'display_name' => $display_name,
            'tn_path'      => $path,
            'file_size'    => $file_size,
        ];

        return true === $this->db->insert('file', $insert_data) ? $this->db->insert_id() : 0;
    }
}