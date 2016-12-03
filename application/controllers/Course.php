<?php

/**
 * Class Course 课程相关
 *
 * @author wangnan <wangnanphp@163.com>
 * @date   16-12-2 15:08
 */
class Course extends Home_Controller
{
    /**
     * index 课程首页
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-02 22:28:01
     */
    public function index()
    {
        // 获取所有学科
        $this->load->model('subject_model');
        $view_var['subjects'] = $this->subject_model->getAll();

        $this->load->view('home/course/index', $view_var);
    }

    /**
     * create 创建课程
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-03 11:22:54
     */
    public function create()
    {
        if ('post' == $this->input->method()) {
            $this->load->library('form_validation');
            if (false === $this->form_validation->run()) {
                http_ajax_response(1, $this->form_validation->error_string());
                return false;
            }

            $create_data = [
                'name'         => (string)$this->input->post('name'),
                'user_id'      => $this->_loginUser['id'],
                'description'  => (string)$this->input->post('desc'),
                'grade_id'     => (int)$this->input->post('grade'),
                'subject'      => (int)$this->input->post('subject'),
                'is_share'     => (int)$this->input->post('share'),
                'is_ready'     => 0, // 默认不发布
                'ul_lon'       => (float)$this->input->post('lt-lng'),
                'ul_lat'       => (float)$this->input->post('lt-lat'),
                'br_lon'       => (float)$this->input->post('rb-lng'),
                'br_lat'       => (float)$this->input->post('rb-lat'),
                'date_created' => date('Y-m-d H:i:s'),
                'tn_file_id'   => (int)$this->input->post('image'),
            ];
            $this->load->model('course_model');
            $insert_id = $this->course_model->create($create_data);
            if (0 >= $insert_id) {
                http_ajax_response(-1, '创建课程失败,请稍后再试!');
                return false;
            }

            http_ajax_response(0, '创建课程成功!');
            return true;
        } else {
            return true;
        }
    }

    /**
     * listing 课程列表
     *
     * @param int $page
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-02 15:37:52
     */
    public function listing($page = 1)
    {
        $this->load->model('course_model');
        $total = $this->course_model->getAllCount();

        if (0 < $total) {
            // Page configure
            $this->load->library('pagination');
            $config['base_url'] = base_url("course/listing");
            $config['total_rows'] = (int)$total;
            $this->pagination->initialize($config);
            $view_var['page'] = $this->pagination->create_links();
            // content
            $view_var['course_list'] = $this->course_model->getPageData($page);
            $this->load->view('home/course/list', $view_var);
        }
    }

    /**
     * delete 删除数据
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-02 22:01:06
     */
    public function delete()
    {
        if ('post' == $this->input->method()) {
            $id = (int)$this->input->post('course');
            if (0 >= $id) {
                http_ajax_response(1, '请求参数有误!');
                return;
            }
            // 删除数据
            $this->load->model('course_model');
            $res = $this->course_model->delById($id);
            if (false === $res) {
                http_ajax_response(-1, '删除课程失败!');
            } else {
                http_ajax_response(0, '成功删除课程!');
            }
        } else {
            http_ajax_response(2, '非法请求!');
            return;
        }
    }

    /**
     * release 发布课程
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-02 23:18:59
     */
    public function release()
    {
        if ('post' == $this->input->method()) {
            $id = (int)$this->input->post('course');
            if (0 >= $id) {
                http_ajax_response(1, '请求参数有误!');
                return;
            }
            // 删除数据
            $this->load->model('course_model');
            $res = $this->course_model->releaseCourseById($id);
            if (true === $res) {
                http_ajax_response(0, '成功发布课程!');
            } else {
                http_ajax_response(-1, '发布课程失败!');
            }
        } else {
            http_ajax_response(2, '非法请求!');
            return;
        }
    }
}
