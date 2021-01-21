<?php

namespace app\api\controller\v1;

use app\api\controller\Base;
use app\api\model\WechatBlogModel;
use app\api\model\WechatUser;
use think\Request;

class WechatBlog extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        $data = $request->param();  //获取发布信息
        $WechatBlogModel = new WechatBlogModel();
        $list = $WechatBlogModel->index($data['videoid']);
        return ['data'=>$list];
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $data = $request->param();  //获取发布信息
        $uid = $this->id;
        $wechatmessageboard = new WechatBlogModel();
        $wechatuser = new WechatUser();
        $username = $wechatuser->where('id', $uid)->field('username')->find(); //查询该用户的用户名
        $logo = $wechatuser->where('id', $uid)->field('logo')->find(); //查询该用户的头像
        $lists = json_decode($data['datas'],true);     //对 JSON 格式的字符串进行解码
        $wechatmessageboard->insert(['videoid' => $lists['videoid'],'username' => $username["username"], 'logo'=> $logo["logo"], 'content' => $lists['content'], 'create_time' => $lists['time']]);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
