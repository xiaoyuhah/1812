<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Price;

class PriceController extends Controller
{
    public function index()
    {
      return view('price/index');
    }

    public function prizeDo()
    {
        //抽奖逻辑处理
        $uid = mt_rand(1,10000);
        $uid = 29;
        //获取用户抽奖记录
        $record = Price::where(['uid'=>$uid])->get()->toArray();
        $count = 0;
        foreach($record as $k=>$v){
            if($v['level'] > 0){        //已中奖
                $level = 0;
                $msg = '您已中过奖了';
                $response = [
                    'errno' => 0,
                    'msg'   => 'ok',
                    'data'  => [
                        'level' => $level,
                        'msg'   => $msg
                    ]
                ];
                die(json_encode($response));
            }
            $count++;
        }
        if($count>=3){
            $response = [
                'errno' => 0,
                'msg'   => 'ok',
                'data'  => [
                    'level' => 0,
                    'msg'   => "抽奖次数已用光"
                ]
            ];
            die(json_encode($response));
        }
        $prize = $this->getPrizeLevel();
        $data = [
            'uid'   => $uid,
            'level' => $prize['level'],
        ];
        $response = [
            'errno' => 0,
            'msg'   => 'ok',
            'data'  => [
                'level' => $prize['level'],
                'msg'   => $prize['msg']
            ]
        ];
        Price::insertGetId($data);
        echo json_encode($response);
    }
    /**
     * 返回中奖等级
     */
    protected function getPrizeLevel()
    {
        $rand_number = mt_rand(1,100);
        $rand_number = 2;
        //判断一等奖个数
        if($rand_number==1){
            $count = Price::where(['level'=>1])->count();
            if($count==1){
                $level = 0;
                $msg = "未中奖";
            }else{
                $level = 1;           //
                $msg = "恭喜 一等奖";
            }
        }elseif($rand_number==2 || $rand_number==3)
        {
            $count = Price::where(['level'=>2])->count();
            if($count==2){
                $level = 0;
                $msg = "未中奖";
            }else{
                $level =  2;           // 二等奖
                $msg = "恭喜 二等奖";
            }
        }elseif($rand_number==4 || $rand_number==5 || $rand_number==6)
        {
            $count = Price::where(['level'=>3])->count();
            if($count==3){
                $level = 0;
                $msg = "未中奖";
            }else{
                $level =  3;           // 二等奖
                $msg = "恭喜 三等奖";
            }
        }elseif($rand_number>6 && $rand_number<17)
        {
            $count = Price::where(['level'=>4])->count();
            if($count==10){
                $level = 0;
                $msg = "未中奖";
            }else{
                $level =  4;           // 二等奖
                $msg = "恭喜 阳光普照奖";
            }
        }else{
            $level = 0;         //未中奖
            $msg = "未中奖";
        }
        return [
            'level' => $level,
            'msg'   => $msg
        ];
    }
}
