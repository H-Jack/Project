<?php


namespace app\api\controller\v1;


use app\api\controller\Base;
use app\api\model\WechatSignin;
use think\Db;
use think\Request;

class WechatShare extends Base
{
    public function getNumber(Request $request)
    {
        $data = $request->param();
        $data = json_decode($data['data']);
        return Db::table('wechat_signin')->where('uid', $data->uid)->where('type', 2)->whereTime("create_time", 'today')->count();
    }
}