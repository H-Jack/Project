<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/9
 * Time: 16:47
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


namespace app\api\model;


use think\Db;

class WechatUser extends Base
{
    protected $table = 'wechat_user';


    public function selectuser($openid,$iphone,$recommendId,$userinfo)
    {
        $list = $this->where('openid',$openid)->find();
        if($list)
            return $list;
        
        $recommendId = intval($recommendId);
        $uid = $this->allowField(true)->insertGetId([
            'iphone' => intval($iphone),
            'openid' => $openid,
            'username' => $userinfo[1],
            "integral" => 0,
            'recommend_id' => $recommendId,
            'logo'      => $userinfo[0],
        ]);

       
        if ($recommendId != 0 && $uid != null) {
            $username = $this->where('id', $recommendId)->value('username');
            //1积分
            $count = $this->isOvertake($recommendId);
            if ($count < 3) {
                $this->insertSignin($recommendId,$username);
                $this->getIntegral($recommendId);
            }
        }
        return $uid;
    }

    private function isOvertake($uid) {
        return Db::table('wechat_signin')->where('uid', $uid)->where('type', 2)->whereTime("create_time", 'today')->count();
    }

    private function insertSignin($uid, $username) {
        $wechatSignin = new WechatSignin();
        $data = ['uid' => $uid, 'number' => 1, 'status' => 1, 'username' => $username, 'type' => 2];
        $wechatSignin->insert($data);
    }

    private function getIntegral($uid) {
        Db::table('wechat_user')
            ->where('id', $uid)
            ->setInc('integral');
    }

    /**
     * 返回用户的积分
     */
    public static function returnintegral($id)
    {
        $result = self::where('id',$id)->value('integral');
        return $result;
    }

    /**
     * 扣除用户积分
     */
    public static function delintegral($id,$cour_integral)
    {
        //计算扣除后的积分
        $user_integral = self::returnintegral($id);
        $num = $user_integral - $cour_integral;
        if($num < 0)
            return false;
        $data = self::where('id',$id)
            ->update(['integral' => $num]);
        return $data;
    }
}