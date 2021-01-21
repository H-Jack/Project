<?php


namespace app\api\service;

use app\api\model\WchatOrdertimeModel;
use app\api\model\WechatSignin;
use app\api\model\WechatUser;

class Signinfunction
{
    //用户签到方法
    public function qiandao($uid)
    {
        $wechatuser = new WechatUser;
        $wechatsignin = new WechatSignin;
        $username = $wechatuser->where('id', $uid)->field('username')->find(); //查询该用户的用户名
        $now_time = date('Y-m-d H:i:s',time()); //现在的时间
        $data = $wechatsignin->where('uid', $uid)->select(); //查询该用户的所有签到记录
        if (count($data) == 0)  //没有该用户的签到记录
        {
            $wechatsignin->insert(['username'=> $username["username"], 'create_time' => $now_time, 'uid' => $uid, 'number' => '1', 'status' => '1','type' => '1']);
            $res = (int)$wechatsignin->where('uid','=',$uid)->sum('number');    //用户签到积分
            $integral = $wechatuser->where('id','=',$uid)->value('integral');   //用户前积分
            $wechatuser->where('id',$uid)->update(['integral' => $res+$integral]);
            return false;
        } else {                //判断今天是否签到
            $todayBegin = date('Y-m-d'." 00:00:00");
            $todayEnd = date('Y-m-d'." 23:59:59");
            $isexit = $wechatsignin->field('create_time')->where('type',1)->where('uid', $uid)->where('create_time', 'between', [$todayBegin, $todayEnd])->select();  //between 查询条件支持字符串或者数组    查询今天是否有签到记录
            if (count($isexit) >= 1) {  //今日已签到
                return true;
            } else    //今日未签到
            {
                if(count($data) == 30){         //如果该用户有30条签到记录
                    $res = (int)$wechatsignin->where('uid','=',$uid)->sum('number');    //遍历该用户的number值的总和
                    $wechatsignin->where('uid','=',$uid)->delete();                     //删除前30条记录
                    $wechatsignin->insert(['username'=> $username["username"],'create_time' => $now_time, 'uid' => $uid, 'number' => $res, 'status' => '1','type' => '1']);  //插入新的签到记录，并将number传入
                    return false;
                }else
                {
                    $wechatsignin->insert(['username'=> $username["username"],'create_time' => $now_time, 'uid' => $uid, 'number' => '1', 'status' => '1','type' => '1']);
                    $integral = $wechatuser->where('id','=',$uid)->value('integral');   //用户前积分
                    $wechatuser->where('id',$uid)->update(['integral' => $integral+"1"]);
                    return false;
                }
            }
        }
    }

    //用户签到状态
    public function qdstatus($uid)
    {
        $wechatsignin = new WechatSignin;
        $yes_end = date('Y-m-d'." 23:59:59",strtotime('-1 day'));   //昨天结束时间
        $datasmall = $wechatsignin->where('type',1)->where('uid', $uid)->where('create_time', '>',$yes_end)->find();;   //大于昨天结束时间的数据
        if ($datasmall === null)
        {
            return true;
        }else
        {
            return false;
        }
    }

}