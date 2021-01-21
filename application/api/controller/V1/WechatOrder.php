<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2021/1/9
 * Time: 13:55
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
use app\api\model\WechatOrder as wechatorderlist;
use think\Facade;

class WechatOrder extends Base
{
    /**
     * 返回全部订单
     */
    public function putorderlist()
    {
        $str = input('get.');
        $strid = [];
        if(!empty($str['id']) && $str['id'] != 'undefined'){
            //获取预约成功的订单
            $list = wechatorderlist::Returntobookingorder($str['id']);
            $arr = [];//预约对应老师id
            foreach ($list as $item){
                //获取老师的二维码
                $strid[$item->subscribe_num] = \app\api\model\TeacherSyno::Getqrcode($item->subscribe_num);
            }
            //把$strid数据返回给小程序  用下标对应id，然后
        }else{
            $list = wechatorderlist::returnallorders();
        }
        return [$list,$strid];
    }
}