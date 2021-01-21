<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/1
 * Time: 20:19
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


namespace app\wechatsamll\model;


use think\Model;

class Coursecontent extends Model
{
    protected $table = 'wechatsamll_coursecontent';

    /**
     * 更新课程编辑数据
     * @param $result
     * @param $classid
     */
    public function editselect($result,$classid)
    {
        $this->allowField(true)->save([
            'teacherimg' => $result['teacherimg'],
            'teachername' => $result['teachername'],
            //'teachersyno' => $result['teachersyno'],
            'coursedeta' => $result['coursedeta'],
        ],['classid' => $classid]);
    }
}