<?php

namespace app\wechatsamll\model;

use think\Model;
use think\Cache;

class WechatOrderModel extends Model
{
    protected $table = 'wechat_ordertime';

    public function index()
    {
        $system_user_id = Cache('system_user_id');      //当前讲师登录id（system_user）
        $teacherphone = \Db::table('system_user')->where('id',$system_user_id)->value('phone'); //老师手机号码
        $teacherid = \Db::table('wechatsamll_teachersyno')->where('phone',$teacherphone)->value('id'); //老师表id
        $today = date('Y-m-d', strtotime(date('Y-m-d', time())));           //获取今天的时间
        $date = \Db::table('wechat_ordertime')->field('tid',$teacherid)->where('date', '<',$today)->select();   //小于今天的时间
        for($yesday=0; $yesday<count($date); $yesday++){                    //删除昨天之前的数据（包括昨天）
            \Db::table('wechat_ordertime')->where('id','=',$date[$yesday]['id'])->delete();
        }
        return array($teacherid,$system_user_id);
    }

    public function _update($data)
    {
        if(isset($data['id'])){
            return $this->save($data,['id'=>$data['id']]);
        }else{
            if($data['date']){
                $weekarray=array("日","一","二","三","四","五","六");
                $week =  "星期".$weekarray[date("w",strtotime($data['date']))]."";    //判断今天是星期几
                $weekarr = ["week" => $week];
                $data = array_merge($data,$weekarr);        //将数组插入data，存入数据库
            }
            if($data['time']){      //预约日期排序
                if($data['time'] == "08:00-09:00"){$timeorder = ["timeorder" => 1];$data = array_merge($data,$timeorder);  }
                if($data['time'] == "09:00-10:00"){$timeorder = ["timeorder" => 2];$data = array_merge($data,$timeorder);  }
                if($data['time'] == "10:00-11:00"){$timeorder = ["timeorder" => 3];$data = array_merge($data,$timeorder);  }
                if($data['time'] == "14:00-15:00"){$timeorder = ["timeorder" => 4];$data = array_merge($data,$timeorder);  }
                if($data['time'] == "15:00-16:00"){$timeorder = ["timeorder" => 5];$data = array_merge($data,$timeorder);  }
                if($data['time'] == "16:00-17:00"){$timeorder = ["timeorder" => 6];$data = array_merge($data,$timeorder);  }
                if($data['time'] == "17:00-18:00"){$timeorder = ["timeorder" => 7];$data = array_merge($data,$timeorder);  }
                if($data['time'] == "18:00-19:00"){$timeorder = ["timeorder" => 8];$data = array_merge($data,$timeorder);  }
                if($data['time'] == "19:00-20:00"){$timeorder = ["timeorder" => 9];$data = array_merge($data,$timeorder);  }
                if($data['time'] == "20:00-21:00"){$timeorder = ["timeorder" => 10];$data = array_merge($data,$timeorder);  }
                if($data['time'] == "21:00-22:00"){$timeorder = ["timeorder" => 11];$data = array_merge($data,$timeorder);  }
                if($data['time'] == "22:00-23:00"){$timeorder = ["timeorder" => 12];$data = array_merge($data,$timeorder);  }
                if($data['time'] == "23:00-00:00"){$timeorder = ["timeorder" => 13];$data = array_merge($data,$timeorder);  }
            }
            $system_user_id = Cache('system_user_id');      //当前讲师登录id（system_user）
            $teacherphone = \Db::table('system_user')->where('id',$system_user_id)->value('phone'); //老师手机号码
            $teacherid = \Db::table('wechatsamll_teachersyno')->where('phone',$teacherphone)->value('id'); //老师表id
            $teachername = \Db::table('wechatsamll_teachersyno')->where('phone',$teacherphone)->value('teachername'); //老师名
            if($teacherphone == ""){
                return 0;
            }
            $teacherarr = ["username" => $teachername, "tid" => $teacherid];
            $data = array_merge($data,$teacherarr);
            return $this->save($data);
        }
    }

    public function get_week($time = '', $format='Y-m-d'){         //获取当周的所有日期
        $time = $time != '' ? $time : time();
        $week = date('w', $time);
        $date = [];
        for ($i=1; $i<=7; $i++){
            $date[$i] = date($format ,strtotime( '+' . $i-$week .' days', $time));
        }
        return $date;
    }
}
