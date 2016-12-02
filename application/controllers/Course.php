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
     * index 列表
     *
     * @param int $page
     *
     * @author wangnan <wangnanphp@163.com>
     * @date 2016-12-02 15:37:52
     */
    public function index(int $page = 1)
    {
        $this->load->model('course_model');
        $total = $this->course_model->getAllCount();

        if(0 < $total) {
            // Page configure
            $this->load->library('pagination');
            $config['base_url'] = base_url("course/index");
            $config['total_rows'] = (int)$total;
            $this->pagination->initialize($config);
            $view_var['page'] = $this->pagination->create_links();
            // content
            $view_var['course_list'] = $this->course_model->getPageData($page);
            $this->load->view('home/course/list', $view_var);
        }
    }
}
