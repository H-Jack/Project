<?php

namespace app\api\controller\v1;

use app\api\controller\Base;
use app\api\model\Mymodel;
use app\api\model\WechatUser;
use app\api\service\Signinfunction;
use think\Request;

class My extends Base
{
    
    public function index()
    {
        $id = $this->id;
        $data = WechatUser::get($id); //查询该用户个人信息
//        $Signinfunction = new Signinfunction();
//        $qdstatus = $Signinfunction->qdstatus($id);      //判断今日是否签到  return true未签到，反之已签到
        return ['data'=>$data];
    }


    public function save(Request $request)
    {
        $data = $request->param();  //获取用户编辑数据
        $id = $this->id;
        $db = new Mymodel();
        $db->_update($data,$id);    //更新数据
    }

    public function qiandao()
    {
        $uid = $this->id;   //该用户的uid
        $Signinfunction = new Signinfunction();
        $qd = $Signinfunction->qiandao($uid);   //点击签到方法(true今日已签到，false今日未签到)
        $integral = \Db::table('wechat_user')->where('id',$uid)->value('integral');
        return ['code'=>1,'msg'=>'请求成功','qdstatus'=>$qd,'integral'=>$integral];
    }

    public function qdstatus()
    {
        $id = $this->id;
        $Signinfunction = new Signinfunction();
        $qdstatus = $Signinfunction->qdstatus($id);      //判断今日是否签到  return true未签到，反之已签到
        $userinfo = WechatUser::get($id); //查询该用户个人信息
        return ['qdstatus'=>$qdstatus,'userinfo'=>$userinfo];
    }
}
