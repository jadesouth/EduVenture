<?php

/**
 * Class Question 问题相关控制器
 *
 * @author wangnan <wangnanphp@163.com>
 * @date   2016-12-05 10:41:19
 */
class Question extends Home_Controller
{
    /**
     * listing 任务列表
     *
     * @param int $page 分页
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-05 10:45:36
     */
    public function listing($page = 1)
    {
        $task_id = (int)$this->input->get('task');
        $this->load->model('question_model');
        $total = $this->question_model->getCount($task_id);

        $view_var = [];
        if (0 < $total) {
            // Page configure
            $this->load->library('pagination');
            $config['base_url'] = base_url("question/listing");
            $config['total_rows'] = (int)$total;
            $this->pagination->initialize($config);
            $view_var['page'] = $this->pagination->create_links();
            // content
            $view_var['question_list'] = $this->question_model->getPageData($task_id, $page);
        }

        http_ajax_response(0, '获取题目数据成功!', $view_var);
    }

    /**
     * createSingleSelect 创建单选题
     *
     * @return bool
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-05 10:51:31
     */
    public function createSingleSelect()
    {
        if ('post' == $this->input->method()) {
            $this->load->library('form_validation');
            if (false === $this->form_validation->run()) {
                http_ajax_response(1, $this->form_validation->error_string());
                return false;
            }

            $name = $this->input->post('name');
            $right = $this->input->post('right');
            $answer = $this->input->post('answer');
            $hotspot_id = $this->input->post('question-task');
            foreach ($answer as $key => $value) {
                $question[] = [
                    'opt' => $value,
                    'is_right' => ($right - 1) == $key ? 1 : 0,
                ];
            }

            $insert_id = $this->create($name, 1, $hotspot_id, $question);
            if (0 >= $insert_id) {
                http_ajax_response(-1, '添加题目失败,请稍后再试!');
                return false;
            }

            http_ajax_response(0, '添加题目成功!');
            return true;
        } else {
            http_ajax_response(2, '非法请求!');
            return true;
        }
    }

    public function createMultipleSelect()
    {

    }

    public function createPicture()
    {

    }

    public function createQA()
    {

    }

    /**
     * create 统一创建问题
     *
     * @param $name
     * @param $type
     * @param $hotspot_id
     * @param $question
     * @return int
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-05 11:09:18
     */
    private function create($name, $type, $hotspot_id, $question)
    {
        $name = (string)$name;
        $type = (int)$type;
        $hotspot_id = (int)$hotspot_id;
        $question = (array)$question;
        if (empty($name) || ! in_array($type,
                [1, 2, 3, 4]) || 0 >= $hotspot_id || empty($question) || ! is_array($question)
        ) {
            return 0;
        }
        // 获取当前任务的课程ID
        $this->load->model('task_model');
        $task_info = $this->task_model->getTask($hotspot_id, 'id,epack_id');
        $course_id = empty($task_info['epack_id']) ? 0 : (int)$task_info['epack_id'];
        if(0 >= $course_id) {
            return 0;
        }
        // 问题内容
        $content = [
            'title' => $name,
            'type' => $type, // 1:单选,2:多选,3:拍照,4:问答
            'question' => $question,
        ];
        $content = json_encode($content, JSON_UNESCAPED_UNICODE);
        // 创建数据
        $create_data = [
            'name'       => $name,
            'kind'       => $type,
            'epack_id'   => $course_id,
            'hotspot_id' => $hotspot_id,
            'content'    => $name,
            'payload'    => $content,
        ];
        $this->load->model('question_model');
        return $this->question_model->create($create_data);
    }

    /**
     * delete 删除题目数据
     *
     * @return bool
     *
     * @author wangnan <wangnanphp@163.com>
     * @date   2016-12-05 11:11:03
     */
    public function delete()
    {
        if ('post' == $this->input->method()) {
            $id = (int)$this->input->post('question');
            if (0 >= $id) {
                http_ajax_response(1, '请求参数有误!');
                return false;
            }
            // 删除数据
            $this->load->model('question_model');
            $res = $this->question_model->delById($id);
            if (false === $res) {
                http_ajax_response(-1, '删除问题失败!');
                return false;
            } else {
                http_ajax_response(0, '成功删除问题!');
                return true;
            }
        } else {
            http_ajax_response(2, '非法请求!');
            return false;
        }
    }
}
