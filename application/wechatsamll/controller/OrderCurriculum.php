<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2021/1/7
 * Time: 10:05
 *
 *    .--,       .--,
 *   ( (  \.---./  ) )
 *    '.__/o   o\__.'
 *       {=  ^  =}
 *        >  -  <
 *       /       \
 *      //       \\
 *     //|   .   |\\
 *     "'\       /'"_.-~^`'-.
 *        \  _  /--'         `
 *      ___)( )(___
 *     (((__) (__)))    高山仰止,景行行止.虽不能至,心向往之.
 *
 */


namespace app\wechatsamll\controller;


use app\wechatsamll\model\OrderTeacherModel;
use library\Controller;
use app\api\model\WechatUser as whercharuses;

class OrderCurriculum extends Controller
{
    /**
     * 绑定当前数据表
     * @var string WechatOrder
     */
    protected $table = 'WechatOrder';

    /**
     * 进入课程订单首页
     * @auth true
     * @menu true
     */
    public function index()
    {
        $this->title = '支付订单';
        $query = $this->_query($this->table)->like('uid,create_at,out_trade_no,total_price,snap_name,transaction_id,status');
        $OrderTeacherSuccess = new OrderTeacherModel;
        $teacherid = $OrderTeacherSuccess->index();
        $useall = $this->searchuser();
        $this->assign('userall',$useall);
        if($teacherid[1] == "10000"){   //如果是超级管理员则查看所有课程订单数据
            $query->dateBetween('create_at')->where("subscribe_num")->where('status',1)->order('id desc')->page();
        }else{
            $teachercoursecolumn = \Db::table('wechatsamll_coursecolumn')->where('teatherid',$teacherid[0])->column('id'); //老师名下的课程id
            $data = array();
            $data['course_num'] = $teachercoursecolumn;
            $query->dateBetween('create_at')->where($data)->where('status',1)->order('id desc')->page();
        }
    }

    /**
     * 获取所有用户
     */
    private function searchuser()
    {
        $user = new whercharuses;
        $list = [];
        $result = $user->field('username,id')->select();
        foreach ($result as $val){
            $list[$val->id] = $val->username;
        }
        return $list;
    }
}