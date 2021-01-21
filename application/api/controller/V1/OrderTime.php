<?php

namespace app\api\controller\v1;

use app\api\controller\Base;
use app\api\model\WchatOrdertimeModel;
use think\Controller;
use think\Request;

class OrderTime extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $tid = input('post.id');
        $WchatOrdertime = new WchatOrdertimeModel();
        $list = $WchatOrdertime->index($tid);  //select出数据
        return json(['code'=>1, 'msg'=>'ok', 'data'=>$list], 200);
    }
}
