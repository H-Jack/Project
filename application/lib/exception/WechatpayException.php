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


class WechatpayException extends BaseException
{
    public $code = 588;

    public $msg = '支付错误';

    public $error = 50202;
}