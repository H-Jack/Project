<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/14
 * Time: 16:31
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
use app\api\model\CourseColumn as courcolumn;
use app\api\validate\TeacherSynolist;
use app\lib\exception\CorrectException;
use app\api\model\WechatOrder;
use think\Facade;

class CourseColumn extends Base
{
    /**
     * 返回全部课程列表
     */
    public function putheacherlist()
    {
        $list = courcolumn::selectlists();
        return $list;
    }


    /**
     * 返回指定id的课程信息
     */
    public function Returnthespecifiedcourse()
    {
        $validate = new TeacherSynolist;
        $id = input('post.id');
        $id = ['id'=>$id];
        if(!$validate->scene('searchteacher')->check($id))
            throw new CorrectException();
        $list = courcolumn::personalist($id)->toArray();
        if(empty($list))
            throw new CorrectException();
        $teacherid = $list['id'];
        //获取关联的老师信息
        $result = $this->Associationacquisitionteacher($teacherid);
        //合并课程数组和老师数组
        $data = array_merge($list,$result);

        $chapterlist = $this->Associationacquisitionchapter($list['id']);
        array_push($data,$chapterlist);
        $ifbuy = $this->Judgewhetherusersbuycourses($list['id']);
        return [$data,$ifbuy];
    }

    /**
     * 通过teacherid关联获取TeacherSyno表老师的数据
     */
    public function Associationacquisitionteacher($teacherid)
    {
        $result = \app\api\model\TeacherSyno::hasWhere('Teachercomments',['id'=>$teacherid])->find();
        return $result->toArray();
    }

    /**
     *关联CourseDetails获取章节的数据
     */
    public function Associationacquisitionchapter($id)
    {
        $result = courcolumn::get($id);
        if ($result)
            $arr_result = $result->toArray();
        $ifbuy = $this->Judgewhetherusersbuycourses($id);
        if($arr_result['price'] == '0.00')
            $ifbuy = true;
        if(!$ifbuy){
            $list = $result->Chaptercomments()->field('videoname,id')->select();
        }else{
            $list = $result->Chaptercomments()->select();
        }
        return $list->toArray();
    }

    /**
     * 判断用户是否购买了订单
     * $coursenum 课程id
     */
    public function Judgewhetherusersbuycourses($coursenum)
    {
        $list = WechatOrder::where('uid',$this->id)
            ->where('course_num',$coursenum)
            ->where('status','1')
            ->find();
        if(!$list)  //购买返回true，没有返回false
            return false;
        return true;
    }
}