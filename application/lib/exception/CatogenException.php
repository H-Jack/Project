<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/9
 * Time: 21:34
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


class CatogenException extends BaseException
{
    public $code = 401;

    public $msg = '没有token';

    public $error = 50000;
}