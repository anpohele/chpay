<?php
namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Config;
use think\facade\Request;
use think\facade\Db;
use think\facade\View;
use think\facade\Cache;
class Contrab extends Command
{
    protected function configure()
    {
        $this->setName('hello')
        	->addArgument('name', Argument::OPTIONAL, "your name")
            ->addOption('city', null, Option::VALUE_REQUIRED, 'city name')
        	->setDescription('Say Hello');
    }

    protected function execute(Input $input, Output $output)
    {
        $this->info();

        
        // $output->writeln("SUCC");

    }
    public function order(){
        Db::startTrans();
        try {
            $trade_no=date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $money=rand(100,500).'.'.rand(10, 99);
            $time=$this->randomDate('2021-05-10 00:00:00',date('Y-m-d H:i:s'));
            $data=[
                'order_id'=>(date('y') + date('m') + date('d')) . str_pad((time() - strtotime(date('Y-m-d'))), 5, 0, STR_PAD_LEFT) . substr(microtime(), 2, 6) . sprintf('%03d', rand(0, 999)),
                'total_num'=>1,
                'total_num'=>$money,
                'total_postage'=>0,
                'pay_price'=>$money,
                'paid'=>1,
                'pay_time'=>strtotime('+53 second',strtotime($time)),
                'pay_type'=>'alipay',
                'add_time'=>strtotime($time),
                'cost'=>$money,
                'shipping_type'=>1,
                'is_channel'=>2,
                'channel_type'=>'h5',
                'unique'=>rand(100,500).(strtotime(date('YmdHis', time()))) . substr(microtime(), 2, 6) . sprintf('%03d', rand(0, 999))
            ];
            //先入库
            $add=db::name('store_order')->insertGetId($data);
            //增加字段，修改
            //购买订单号
            $upd['trade_no']=$trade_no;
            //随机用户id
            $user=db::name('user')->select();
            $users=[];
            foreach ($user as $uk=>$uv){
                $users[$uk]=$uv['uid'];
            }
            $key=array_rand($users,1);
            $upd['uid']=$users[$key];
            $upd['real_name']=$user[$key]['real_name'];
            $upd['user_address']=$user[$key]['addres'];
            //手机号
            $tel_arr = array(
                '130','131','132','133','134','135','136','137','138','139','144','147','150','151','152','153','155','156','157','158','159','176','177','178','180','181','182','183','184','185','186','187','188','189',
            );
            for($i = 0; $i < 1; $i++) {
                $tmp[] = $tel_arr[array_rand($tel_arr)].mt_rand(1000,9999).mt_rand(1000,9999);
                // $tmp[] = $tel_arr[array_rand($tel_arr)].'xxxx'.mt_rand(1000,9999);
            }
            $upd['user_phone']=$tmp[0];
            //订单状态（-1 : 申请退款 -2 : 退货成功 0：待发货；1：待收货；2：已收货；3：待评价；-1：已退款）
            $status=array(0=>"0",1=>"1",2=>"2",3=>"3");
            $key=array_rand($status,1);
            $upd['status']=$status[$key];
            $updstatus=db::name('store_order')->where('id',$add)->update($upd);
            //生成操作记录
            $data=[
                0=>[
                    'oid'=>$add,
                    'change_message'=>'订单生成',
                    'change_type'=>'cache_key_create_order',
                    'change_time'=>$data['add_time']
                ],
                1=>[
                    'oid'=>$add,
                    'change_message'=>'用户付款成功',
                    'change_type'=>'pay_success',
                    'change_time'=>$data['pay_time']
                ],
            ];
            $addstatus=db::name('store_order_status')->insertAll($data);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            echo '执行失败';
            // 回滚事务
            Db::rollback();
        }
    }
    //生成数据
    public function info(){
        for($a=0;$a<10;$a++){
            $this->order();
            echo '执行成功'.$a;
        }
    }
    function randomDate($begintime, $endtime="", $now = true) {
        $begin = strtotime($begintime);
        $end = $endtime == "" ? mktime() : strtotime($endtime);
        $timestamp = rand($begin, $end);
        // d($timestamp);
        return $now ? date("Y-m-d H:i:s", $timestamp) : $timestamp;
    }
}