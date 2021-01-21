<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/10
 * Time: 12:37
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
use app\api\model\TeacherSyno as syno;
use app\api\validate\TeacherSynolist;
use app\lib\exception\CorrectException;
use think\Request;

class TeacherSyno extends Base
{
    /**
     * 返回全部教师列表
     */
    public function putheacherlist()
    {
        $list = syno::selectlist();
        return $list;
    }

    /**
     * 名师栏目点击老师，返回点击个人老师的信息
     */
    public function puteachetcont()
    {
        //获取GET的id验证数据
        //通过id获取到老师信息
        //返回数据
        $validate = new TeacherSynolist;
        $id = input('get.id');
        $id = ['id'=>$id];
        if(!$validate->scene('searchteacher')->check($id))
            throw new CorrectException();
        $lists = syno::personalist($id)->toArray();
        if(empty($lists))
            throw new CorrectException();
        $courseslist = $this->Teachercourses($id['id']);

        if(!empty($courseslist))
           $list = [];
           $list[0] = $lists;
           $list[1] = $courseslist;
        return $list;
    }
    /**
     * 获取关联的老师课程数据
     */
    public function Teachercourses($id)
    {
       $result =  syno::get($id);
       return $result->Teachercomments()->select()->toArray();
    }
}