<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/8
 * Time: 21:11
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


namespace app\api\controller\v1;


use app\api\controller\Base;
use app\api\controller\EasyWeChats;
use app\api\model\WechatSignin;
use app\api\model\WechatUser;
use Firebase\JWT\JWT;
use think\Request;
use app\api\model\Login as loginadmin;
use app\api\service\Jwtcustom;
use think\Collection;

class Login
{
    public function login(Request $request)
    {
        //微信获取code和个人信息
        $data = $request->param();
        $data2 = json_decode($data['data2']);
        $data = json_decode($data['data']);
        $recommendId = $data->recommendId;

        //通过code获取openid
        $wechatlist = EasyWeChats::login_wechat($data->code);

        //新用户就把解密个人信息
        $wechatencry = EasyWeChats::encry_wechat($wechatlist['session_key'],$data->iv,$data->encryptedData);

        //通过openid数据库查询判断是否为新用户，为新用户将openid等信息插入数据库并返回给token
        $wechatuser = new WechatUser;
        $list = $wechatuser->selectuser($wechatlist['openid'],$wechatencry['phoneNumber'],$recommendId,$data2);

        if(is_string($list)){
            $tokenList = Jwtcustom::getoken($list);
        }else{
            $tokenList = Jwtcustom::getoken($list->id);
        }

        return json(['msg'=>'ok','tokenList'=>$tokenList],200);

    }

}