<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/2
 * Time: 15:22
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

class Teachersyno extends Model
{
    protected $table = 'wechatsamll_teachersyno';

    public function bindcommon()
    {
        return $this->hasOne('Coursecolumn','teatherid','id');
    }
}