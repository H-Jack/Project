<?php
    function qiandao($openid)
    {
        $data = \Db::table('wechat_signin')->where('openid', $openid)->select();
        if (count($data) == 0)  //没有该用户的签到记录
        {
            \Db::table('wechat_signin')->insert(['create_time' => date('Y-m-d H:i:s'), 'openid' => $openid, 'number' => '1']);
            return 1;
        } else {                //判断今天是否签到
            $todayBegin = date('Y-m-d' . " 00:00:00");
            $todayEnd = date('Y-m-d' . " 23:59:59");
            $isexit = \Db::table('wechat_signin')->field('create_time')->where(['userid' => $openid])->where('create_time', 'between', [$todayBegin, $todayEnd])->select();  //between 查询条件支持字符串或者数组
            if (count($isexit) == 1) {  //今日已签到z
                return 0;
            } else    //今日未签到
            {
                \Db::table('wechat_signin')->insert(['create_time' => date('Y-m-d H:i:s'), 'openid' => $openid, 'number' => '1']);
                return 1;
            }
        }
    }
    ?>
