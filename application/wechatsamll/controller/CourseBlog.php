<?php


namespace app\wechatsamll\controller;


use app\admin\validate\GoodsValidate;
use app\wechatsamll\model\BlogModel;
use library\Controller;
use library\service\MenuService;
use library\tools\Data;
use think\Request;

/**
 * 课程评论
 * Class CourseColumn
 * @package app\wechatsamll\controller
 */
class CourseBlog extends Controller
{
    /**
     * 绑定当前数据表
     * @var string WechatBlog
     */
    protected $table = 'WechatBlog';

    /**
     * 进入课程评论首页
     * @auth true
     * @menu true
     */
    public function index()
    {
        $this->title = '课程评论';
        $query = $this->_query($this->table)->like('');
        $query->dateBetween('create_at')->order('id desc')->page();
    }

    /**
     * @auth true
     * 删除评论
     */
    public function remove()
    {
        $this->applyCsrfToken();
        $this->_delete($this->table);
    }
    /**
     * @auth true
     * 编辑评论
     */
    public function edit()
    {
        $this->applyCsrfToken();
        $this->_form($this->table, 'edit');
    }
    /**
     * @auth true
     * 保存编辑评论
     */
    public function save(Request $request)
    {
        $data = $request->param();
        $db = new BlogModel();
        $res = $db->_update($data);
        if($res){
            return ['code'=>1, 'msg'=>'操作成功'];
        }else{
            return ['code'=>0, 'msg'=>'操作失败'];
        }
    }
}