<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/1
 * Time: 20:34
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

class Coursecolumn extends Model
{
    protected $table = 'wechatsamll_coursecolumn';

    public function editsave($classid,$result)
    {
        $this->allowField(true)->save([
            'price' => $result['price'],
            'coursename' => $result['coursename'],
            'coursedetails' => $result['datail']['coursedeta']
        ],['id'=>$classid]);
    }

}