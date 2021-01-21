<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/7
 * Time: 10:34
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


use think\Controller;
use app\wechatsamll\model\Testclass as modeltest;

class Testclass extends Controller
{
    /**
     * 测试模块
     */
    public function test()
    {
        $lgc = new modeltest;
        $result = $lgc->paginate(1);
        $this->assign('list',$result);
        return $this->fetch();
    }
}