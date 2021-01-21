<?php

namespace app\wechatsamll\model;

use think\Model;
use think\Cache;

class OrderTeacherModel extends Model
{
    protected $table = 'WechatOrder';

    public function index()
    {
        $system_user_id = Cache('system_user_id');      //当前讲师登录id（system_user）
        $teacherphone = \Db::table('system_user')->where('id',$system_user_id)->value('phone'); //老师手机号码
        $teacherid = \Db::table('wechatsamll_teachersyno')->where('phone',$teacherphone)->value('id'); //老师表id
        return array($teacherid,$system_user_id);
    }
}
