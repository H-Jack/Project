<?php


namespace app\wechatsamll\controller;


use app\admin\validate\GoodsValidate;
use app\wechatsamll\model\MessageboardModel;
use app\wechatsamll\model\SmallProgramIndexModel;
use library\Controller;
use library\service\MenuService;
use library\tools\Data;
use think\Request;

/**
 * 留言板
 * Class MessageBoard
 * @package app\wechatsamll\controller
 */
class SmallProgramIndex extends Controller
{
    /**
     * 绑定当前数据表
     * @var string WechatBlog
     */
    protected $table = 'wechatsmall_programindex';

    /**
     * 进入小程序首页
     * @auth true
     * @menu true
     */
    public function index()
    {
        $this->title = '小程序首页';
        $query = $this->_query($this->table)->like('');
        $query->dateBetween('create_at')->order('id desc')->page();
    }
    /**
     * @auth true
     * 视频公司简介留言
     */
    public function edit()
    {
        $this->applyCsrfToken();
        $this->_form($this->table, 'edit');
    }
    /**
     * @auth true
     * 保存编辑视频公司简介
     */
    public function save(Request $request)
    {
        $data = $request->param();
        $db = new SmallProgramIndexModel();
        $res = $db->_update($data);
        if($res){
            return ['code'=>1, 'msg'=>'操作成功'];
        }else{
            return ['code'=>0, 'msg'=>'操作失败'];
        }
    }
}