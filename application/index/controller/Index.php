<?php


namespace app\index\controller;

use library\Controller;

/**
 * 应用入口
 * Class Index
 * @package app\index\controller
 */
class Index extends Controller
{
    /**
     * @auth true  # 表示需要验证权限
     *  @menu true
     * 入口跳转链接
     */
    public function index()
    {
        $query = $this->_query('system_user')->where('username','=','admin');
        $query->page();
    }

    public function hszs()
    {
        return 'sdd';
    }
}
