<?php

namespace app\wechatsamll\controller;

use app\wechatsamll\model\WechatOrderModel;
use library\Controller;
use think\Request;

class WechatOrdertime extends Controller
{
    /**
     * 绑定当前数据表
     * @var string WechatOrdertime
     */
    protected $table = 'WechatOrdertime';

    /**
     * 显示讲师预约
     * @auth true
     * @menu true
     * @return \think\Response
     */
    public function index()
    {
        $this->title = '讲师预约';
        $WechatOrderModel = new WechatOrderModel();
        $teacherid = $WechatOrderModel->index();
        $query = $this->_query($this->table)->like('username,date,time');;
        if($teacherid[1] == "10000"){   //如果是超级管理员则查看所有数据
            $query->dateBetween('create_at')->order('date')->order('timeorder')->page();
        }else{      //查看当前老师的预约数据
            $query->dateBetween('create_at')->where('tid',$teacherid[0])->order('date')->order('timeorder')->page();
        }
    }

    /**
     * @auth true
     * 编辑讲师预约
     */
    public function edit()
    {
        $this->applyCsrfToken();
        $this->_form($this->table, 'edit');
    }

    /**
     * @auth true
     * 删除讲师预约
     */
    public function remove()
    {
        $this->applyCsrfToken();
        $this->_delete($this->table);
    }

    /**
     * @auth true
     * 保存讲师预约
     */
    public function save(Request $request)
    {
        $data = $request->param();
        $db = new WechatOrderModel();
        $res = $db->_update($data);
        if(!$res == 0){
            return ['code'=>1, 'msg'=>'操作成功'];
        }else{
            return ['code'=>0, 'msg'=>'请登录老师账号预约！'];
        }
    }

    /**
     * @auth true
     * 添加预约
     */
    public function add()
    {
        $this->title = '添加测试';
        $WechatOrderModel = new WechatOrderModel();
        $week = $WechatOrderModel->get_week();
        $this->assign('date',$week);        //将变量输出到view图层
        $this->_form($this->table, 'add');
    }
}
