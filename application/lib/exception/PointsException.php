<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/10
 * Time: 18:30
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


namespace app\lib\exception;


class PointsException extends BaseException
{
    public $code = 513;

    public $msg = '积分扣除失败，用户积分不够';

    public $error = 12345;
}