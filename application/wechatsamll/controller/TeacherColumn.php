<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/2
 * Time: 14:03
 *
 *    .--,       .--,
 *   ( (  \.---./  ) )
 *    '.__/o   o\__.'
 *       {=  ^  =}
 *        >  -  <
 *       /       \
 *      //       \\
 *     //|   .   |\\
 *     "'\       /'"_.-~^`'-.
 *        \  _  /--'         `
 *      ___)( )(___
 *     (((__) (__)))    高山仰止,景行行止.虽不能至,心向往之.
 *
 */


namespace app\wechatsamll\controller;


use app\api\model\WchatOrdertime;
use library\Controller;
use think\Db;
use app\wechatsamll\model\Teachersyno;

class TeacherColumn extends Controller
{
    /**
     * 绑定当前数据表
     * @var string
     */
    protected $table = 'WechatsamllTeachersyno';   //课程列表表
    protected $wchatordertime = 'wechat_ordertime';   //预约讲师列表表

    /**
     * 表单结果处理
     * @param boolean $result
     */
    protected function _form_result($result)
    {
        if($this->title == '添加教师')
        {
            //$teachersyno = new Teachersyno();
            $teacherid = $result + mt_rand(100,999);
            $teachersyno = Teachersyno::get($result);
            $teachersyno->save([
               'teacherid' =>  $teacherid
            ]);

        }
    }
    /**
     * 进入老师管理首页
     * @auth true
     * @menu true
     */
    public function index()
    {
        $this->title = '课程栏目管理';
        $query = $this->_query($this->table);
        $query->dateBetween('create_at')->order('id desc')->page();
    }

    /**
     * @auth true
     * 删除老师
     */
    public function remove()
    {
        $this->applyCsrfToken();
        $this->_delete($this->table);
    }

    /**
     * @auth true
     * 添加老师
     */
    public function add()
    {
        $this->title = '添加教师';
        $this->_form($this->table, 'form');
    }

    /**
     * @auth true
     * 编辑老师
     */
    public function edit()
    {
        $this->title = '编辑教师';
        $this->_form($this->table, 'edit');
    }

}