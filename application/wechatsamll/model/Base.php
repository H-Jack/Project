<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/8
 * Time: 20:52
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

class Base extends Model
{
    protected function nametest($value)
    {
        return $value.'这是修改后的';
    }
}