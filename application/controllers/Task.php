<?php

/**
 * Class Task
 *
 * @author wangnan <wangnanphp@163.com>
 * @date   2016-12-04 12:52:02
 */
class Task extends Home_Controller
{
    /**
     * listing 任务列表
     *
     * @param int $page 分页
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-04 13:34:42
     */
    public function listing($page = 1)
    {
        $course_id = (int)$this->input->get('course');
        $this->load->model('task_model');
        $total = $this->task_model->getCount($course_id);

        $view_var = [];
        if (0 < $total) {
            // Page configure
            $this->load->library('pagination');
            $config['base_url'] = base_url("task/listing");
            $config['total_rows'] = (int)$total;
            $this->pagination->initialize($config);
            $view_var['page'] = $this->pagination->create_links();
            // content
            $view_var['task_list'] = $this->task_model->getPageData($course_id, $page);
        }

        http_ajax_response(0, '获取任务数据成功!', $view_var);
    }

    /**
     * create 创建任务
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-04 20:13:35
     */
    public function create()
    {
        if ('post' == $this->input->method()) {
            $this->load->library('form_validation');
            if (false === $this->form_validation->run()) {
                http_ajax_response(1, $this->form_validation->error_string());
                return false;
            }
            // 创建数据
            $create_data = [
                'epack_id' => (int)$this->input->post('task-course'),
                'name'     => (string)$this->input->post('name'),
                'color'    => (int)$this->input->post('task-image'),
                'lat'      => (float)$this->input->post('task-center-lat'),
                'lon'      => (float)$this->input->post('task-center-lng'),
            ];
            $this->load->model('task_model');
            $insert_id = $this->task_model->create($create_data);
            if (0 >= $insert_id) {
                http_ajax_response(-1, '创建任务失败,请稍后再试!');
                return false;
            }

            http_ajax_response(0, '创建任务成功!');
            return true;
        } else {
            $epack_id = (int)$this->input->get('course');
            if (0 >= $epack_id) {
                http_ajax_response(2, '非法请求!');
                return false;
            }

            // 获取课程区域坐标
            $this->load->model('course_model');
            $latlng = $this->course_model->getCourse($epack_id, 'id,ul_lon,ul_lat,br_lon,br_lat');
            if (empty($latlng)) {
                http_ajax_response(3, '非法请求!');
                return false;
            }
            http_ajax_response(0, '获取课程数据成功!', $latlng);
            return true;
        }
    }

    /**
     * modify 修改任务
     *
     * @return bool
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-04 22:08:37
     */
    public function modify()
    {
        if ('post' == $this->input->method()) {
            $task_id = (int)$this->input->post('edit-course-task');
            if (0 >= $task_id) {
                http_ajax_response(2, '非法操作!');

                return false;
            }
            $this->load->library('form_validation');
            if (false === $this->form_validation->run()) {
                http_ajax_response(1, $this->form_validation->error_string());

                return false;
            }
            // 修改数据
            $update_data = [
                'name'  => (string)$this->input->post('edit-task-name'),
                'color' => (int)$this->input->post('edit-task-image'),
                'lat'   => (float)$this->input->post('task-center-lat'),
                'lon'   => (float)$this->input->post('task-center-lng'),
            ];
            $this->load->model('task_model');
            $res = $this->task_model->modify($task_id, $update_data);
            if (false === $res) {
                http_ajax_response(-1, '修改任务失败,请稍后再试!');

                return false;
            }

            http_ajax_response(0, '修改任务成功!');

            return true;
        } else {
            $task_id = (int)$this->input->get('task');
            if (0 >= $task_id) {
                http_ajax_response(1, '非法请求!');
            }
            $this->load->model('task_model');
            $fields = 'id,epack_id,name,color,lat,lon';
            $view_var['task'] = $this->task_model->getTask($task_id, $fields);
            if (empty($view_var['task'])) {
                http_ajax_response(2, '当前任务不存在!');
            } else {
                // 获取课程区域坐标
                $this->load->model('course_model');
                $view_var['latlng'] = $this->course_model->getCourse($view_var['task']['epack_id'], 'id,ul_lon,ul_lat,br_lon,br_lat');
                http_ajax_response(0, '获取课程数据成功!', $view_var);
            }

            return true;
        }
    }

    /**
     * delete 删除任务数据
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-04 21:49:43
     */
    public function delete()
    {
        if ('post' == $this->input->method()) {
            $id = (int)$this->input->post('task');
            if (0 >= $id) {
                http_ajax_response(1, '请求参数有误!');

                return;
            }
            // 删除数据
            $this->load->model('task_model');
            $res = $this->task_model->delById($id);
            if (false === $res) {
                http_ajax_response(-1, '删除任务失败!');
            } else {
                http_ajax_response(0, '成功删除任务!');
            }
        } else {
            http_ajax_response(2, '非法请求!');

            return;
        }
    }
}