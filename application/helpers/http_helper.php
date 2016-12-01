<?php

/**
 * HTTP 相关辅助函数
 *
 * @author wangnan
 * @date 2016-05-04 11:41:36
 */
if ( ! function_exists('http_ajax_response'))
{
    /**
     * http_ajax_response   Http ajax请求的响应
     *
     * @param int $status_code 响应码 [0:OK,其他:Error]
     * @param string $msg 响应的消息,一般用于失败时的错误消息
     * @param array $data 相关数据
     *
     * @author wangnan
     * @date 2016-05-04 11:50:16
     */
    function http_ajax_response($status_code = 0, $msg = '', $data = [])
    {
        http_json_response(['status' => (int)$status_code, 'msg' => (string)$msg, 'data' => $data]);
    }
}

if ( ! function_exists('http_json_response'))
{
    /**
     * http_json_response http json 响应
     * 并未直接输出到前端,脚本执行结束时将自动输出响应
     *
     * @param array $data JSON响应的数组
     *
     * @author wangnan
     * @date 2016-05-04 11:42:28
     */
    function http_json_response(array $data)
    {
        get_instance()->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data, JSON_UNESCAPED_UNICODE));
    }
}
