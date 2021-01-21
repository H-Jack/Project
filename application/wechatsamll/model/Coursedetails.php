<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/1
 * Time: 16:23
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

class Coursedetails extends Model
{
    protected $table = 'wechatsamll_coursedetails';

    /**
     * @param $resultlike array
     * @param $resultname array
     * @param $classid   int
     */
    public function editsavelike($resultlike,$resultname,$classid)
    {
        $list = $this->where('classid',$classid)->order('orders','asc')->select();
        foreach ($list as $i=>$v)
        {
            $v->allowField(true)->save(
                [
                    'videolike'=>$resultlike[$i],
                    'videoname'=>$resultname[$i]
                ]
            );
        }
    }
}