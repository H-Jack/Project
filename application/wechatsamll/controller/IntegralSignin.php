<?php

namespace app\wechatsamll\controller;

use app\wechatsamll\model\IntegralSigninModel;
use library\Controller;
use think\Request;

class IntegralSignin extends Controller
{
    /**
     * 绑定当前数据表
     * @var string WechatSignin
     */
    protected $table = 'WechatSignin';

    /**
     * 显示打卡签到记录列表列表
     * @auth true
     * @menu true
     * @return \think\Response
     */
    public function index()
    {
        $this->title = '打卡记录';
        $yes_end = date('Y-m-d'." 23:59:59",strtotime('-1 day'));   //昨天结束时间
        $datasmall = \Db::table('wechat_signin')->where('create_time', '<=',$yes_end)->select();   //小于昨天结束时间的数据
        $databig = \Db::table('wechat_signin')->where('create_time', '>',$yes_end)->select();   //大于昨天结束时间的数据
        for ($x=0; $x<count($datasmall); $x++) {    //小于等于昨天结束时间数据的状态改为0 今日未签到
            $test = $data[$x]["status"]=0;
            $db = new IntegralSigninModel();
            $db->_updateStatus($test,$datasmall[$x]["id"]);
        };
        for ($y=0; $y<count($databig); $y++) {     //大于等于昨天结束时间数据的状态改为1 今日已签到
            $test2 = $data[$x]["status"]=1;
            $db = new IntegralSigninModel();
            $db->_updateStatus($test2,$databig[$y]["id"]);
        };
        $query = $this->_query($this->table);
        $query->dateBetween('create_at')->where('type',1)->order('id desc')->page();
    }

    /**
     * @auth true
     * 删除签到记录
     */
    public function remove()
    {
        $this->applyCsrfToken();
        $this->_delete($this->table);
    }
    /**
     * @auth true
     * 添加课程
     */
    public function add()
    {
        $this->title = '添加测试';
        $this->_form($this->table, 'test');
    }
    /**
     * @auth true
     * 编辑签到记录
     */
    public function edit()
    {
        $this->applyCsrfToken();
        $this->_form($this->table, 'edit');
    }
    /**
     * @auth true
     * 保存签到记录
     */
    public function save(Request $request)
    {
        $data = $request->param();
        $db = new IntegralSigninModel();
        $res = $db->_update($data);
        if($res){
            return ['code'=>1, 'msg'=>'操作成功'];
        }else{
            return ['code'=>0, 'msg'=>'操作失败'];
        }
    }

    public function qiandao(Request $request)
    {
        $data = $request->param();
        $uid = $data['uid'];
        $username = \Db::table('wechat_user')->where('id', $uid)->field('username')->find(); //查询该用户的用户名
        $now_time = date('Y-m-d H:i:s',time()); //现在的时间
        $data = \Db::table('wechat_signin')->where('uid', $uid)->select(); //查询该用户的所有签到记录
        if (count($data) == 0)  //没有该用户的签到记录
        {
            \Db::table('wechat_signin')->insert(['username'=> $username["username"], 'create_time' => $now_time, 'uid' => $uid, 'number' => '1', 'status' => '1']);
            return ['code'=>1, 'msg'=>'签到成功'];
        } else {                //判断今天是否签到
            $todayBegin = date('Y-m-d'." 00:00:00");
            $todayEnd = date('Y-m-d'." 23:59:59");
            $isexit = \Db::table('wechat_signin')->field('create_time')->where('uid', $uid)->where('create_time', 'between', [$todayBegin, $todayEnd])->select();  //between 查询条件支持字符串或者数组    查询今天是否有签到记录
            if (count($isexit) >= 1) {  //今日已签到
                return ['code'=>0, 'msg'=>'该用户今日已签到'];
            } else    //今日未签到
            {
                if(count($data) == 30){         //如果该用户有30条签到记录
                    $res = (int)\Db::table('wechat_signin')->where('uid','=',$uid)->sum('number');    //遍历该用户的number值的总和
                    \Db::table('wechat_signin')->where('uid','=',$uid)->delete();                     //删除前30条记录
                    \Db::table('wechat_signin')->insert(['username'=> $username["username"],'create_time' => $now_time, 'uid' => $uid, 'number' => $res, 'status' => '1']);  //插入新的签到记录，并将number传入
                    return ['code'=>1, 'msg'=>'签到成功'];
                }
                \Db::table('wechat_signin')->insert(['username'=> $username["username"],'create_time' => $now_time, 'uid' => $uid, 'number' => '1', 'status' => '1']);
                return ['code'=>1, 'msg'=>'签到成功'];
            }
        }
    }
}
