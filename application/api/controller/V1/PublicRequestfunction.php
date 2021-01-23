<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2021/1/12
 * Time: 21:37
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


namespace app\api\controller\V1;


use app\api\controller\Base;
use think\Db;
use think\Facade;
use app\api\controller\v1\CourseColumn;
use app\api\controller\v1\TeacherSyno;
use app\api\controller\v1\My as myindex;
use app\api\model\TeacherSyno as teacherelation;

class PublicRequestfunction extends Base
{
    /**
     * 返回所有的老师和课程信息，包括长度
     * @return array
     */
    public function Returntoallcoursesandteachers()
    {
        //返回课程
        $abj = new CourseColumn;
        $courses = $abj->putheacherlist();
        $courseslist = $this->Coursedatarewriting($courses);

        //返回老师
        $abj = new TeacherSyno;
        $teachers = $abj->putheacherlist();
        //返回总量
        $userabj = new myindex;
        $userinfo = $userabj->index();

        //返回小程序首页预览视频链接、公司简介链接
        $SamllProgramIndexabj = new myindex;
        $SamllProgramIndex = $userabj->SamllProgramIndex();
        //返回可积分兑换课程
        $integralcourse = \app\api\model\CourseColumn::integralcourselist();

        $list = ['userinfo' => $userinfo, 'courses' => $courseslist, 'teachers' => $teachers, 'count' => ['courses' => count($courses), 'teachers' => count($teachers)], 'integralcourse' => $integralcourse, 'SamllProgramIndex'=> $SamllProgramIndex];
        return $list;
    }

    /**
     * 课程数据里添加老师数据
     */
    private function Coursedatarewriting($courses)
    {
        $courseslist = [];
        //获取老师表数据库实例化
        $relation = new teacherelation;
        //获取
        foreach ($courses as $item){
            $data = $relation::hasWhere('Associatedselftable',['id'=>$item->id])->select();
            $teacherlist = $data->toArray();
            $result = $item->toArray();
            $result = array_merge($result,$teacherlist);
            array_push($courseslist,$result);
        }
        return $courseslist;
    }



    /**
     * 判断前台获取的长度判断可以得知有没有新增或者修改，然后更新缓存，第二次打开可以查看到效果
     */
    public function Judgewhethertoupdate()
    {
        $list = json_decode(input('data'));
        switch ($list[1]) {
            case 'courses':
                $resnum = Db::table('wechatsamll_coursecolumn')->count('id');
                if ($resnum != $list[0])
                    return $this->Returntoallcoursesandteachers();
                break;
            case 'teachers':
                $resnum = Db::table('wechatsamll_teachersyno')->count('id');
                if ($resnum != $list[0])
                    return $this->Returntoallcoursesandteachers();
                break;
        }
    }
}