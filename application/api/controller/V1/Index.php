<?php

namespace app\api\controller\v1;

use app\api\controller\Base;
use app\api\model\WechatSignin as weuser;

class Index extends Base
{
    
    public function index()
    {
        $data = [
            'username' => '张三',
            'age'   => 38
        ];
        $id = $this->id;



        return ['code'=>1,'msg'=>'请求成功','data'=>$data,'id'=>$id];
    }

    public function hjh()
    {
        $weuser = new weuser;
        $yes_end = date('Y-m-d'." 23:59:59",strtotime('-1 day'));   //昨天结束时间
        $datasmall = $weuser->where('uid', 4)->where('create_time', '<=',$yes_end)->find();   //小于
        echo weuser::getLastSql();
    }

}
