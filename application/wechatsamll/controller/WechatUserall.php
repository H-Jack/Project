<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2021/1/7
 * Time: 18:06
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


use library\Controller;
use app\api\model\WechatUser as whercharuses;

class WechatUserall extends Controller
{

    /**
     * 绑定当前数据表
     * @var string WechatBlog
     */
    protected $table = 'WechatUser';

    /**
     * 进入用户首页
     * @auth true
     * @menu true
     */
    public function index()
    {
        $this->title = '微信用户列表';
        $query = $this->_query($this->table)->like('iphone');
        $useall = $this->getreferences();
        $this->assign('userall',$useall);
        $query->dateBetween('create_at')->order('id desc')->page();
    }

    /**
     * 获取推荐人
     */
    private function getreferences()
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