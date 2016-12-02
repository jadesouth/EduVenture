<?php
/**
 * Created by PhpStorm.
 * User: wangnan
 * Date: 16-5-12
 * Time: 下午23:09
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class MY_Upload 扩展上传类库
 */
class MY_Upload extends CI_Upload
{
    /**
     * Validate Upload Path 扩展父类的上传文件路径检测函数
     * 主要扩展路径不存在的时候自动创建路径
     *
     * Verifies that it is a valid upload path with proper permissions.
     *
     * @return  bool
     * @author wangann
     * @date 2016-05-12 23:08:02
     */
    public function validate_upload_path()
    {
        if ($this->upload_path === '')
        {
            $this->set_error('upload_no_filepath', 'error');
            return FALSE;
        }

        if (realpath($this->upload_path) !== FALSE)
        {
            $this->upload_path = str_replace('\\', '/', realpath($this->upload_path));
        }

        if ( ! is_dir($this->upload_path))
        {
            if(! is_file($this->upload_path))
            {
                @mkdir($this->upload_path, 0755, true);
            }
            if(! is_dir($this->upload_path))
            {
                $this->set_error('upload_no_filepath', 'error');
                return FALSE;
            }
        }

        if ( ! is_really_writable($this->upload_path))
        {
            $this->set_error('upload_not_writable', 'error');
            return FALSE;
        }

        $this->upload_path = preg_replace('/(.+?)\/*$/', '\\1/',  $this->upload_path);
        return TRUE;
    }
}
