<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Auction;
use App\Enditem;
use App\Item;
class auction_update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'item:success_update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command item_success update';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      // 진행중 -> 진행 완료
      Item::where('item_deadline','<=', date('Y-m-d'))->update([
        'item_success' => 0
      ]);
      // 판매완료인 물품 -> 순위 나누기
      $item_number = Item::select('item_number')->where(['item_success'=> 0])->get();
      for($i = 0; $i < count($item_number); $i++){
        $auciton = Auction::select('item_price', 'buyer_ID')->where([
          'auction_itemnum'=>$item_number[$i]->item_number
          ])->orderBy('item_price', 'desc')->get();

          $Enditem = Enditem::select('*')->where(['end_num'=>$item_number[$i]->item_number])->get()->count();

          if($Enditem==0){
            if(count($auciton)>=5){
              $data = new Enditem([
                'success_price1'=>$auciton[0]->item_price,
                'success_price2'=>$auciton[1]->item_price,
                'success_price3'=>$auciton[2]->item_price,
                'success_price4'=>$auciton[3]->item_price,
                'success_price5'=>$auciton[4]->item_price,
                'success_user1'=>$auciton[0]->buyer_ID,
                'success_user2'=>$auciton[1]->buyer_ID,
                'success_user3'=>$auciton[2]->buyer_ID,
                'success_user4'=>$auciton[3]->buyer_ID,
                'success_user5'=>$auciton[4]->buyer_ID,
                'success_date'=> date('Y-m-d'),
                'buyer' => $auciton[0]->buyer_ID,
                'end_num'=> $item_number[$i]->item_number
              ]);
              $data->save();
              Item::where(['item_number'=> $item_number[$i]->item_number])->update([
                'success' => 1
              ]);
            }
            elseif (count($auciton)==4) {
              $data = new Enditem([
                'success_price1'=>$auciton[0]->item_price,
                'success_price2'=>$auciton[1]->item_price,
                'success_price3'=>$auciton[2]->item_price,
                'success_price4'=>$auciton[3]->item_price,
                'success_user1'=>$auciton[0]->buyer_ID,
                'success_user2'=>$auciton[1]->buyer_ID,
                'success_user3'=>$auciton[2]->buyer_ID,
                'success_user4'=>$auciton[3]->buyer_ID,
                'success_date'=> date('Y-m-d'),
                'buyer' => $auciton[0]->buyer_ID,
                'end_num'=> $item_number[$i]->item_number
              ]);
              $data->save();
              Item::where(['item_number'=> $item_number[$i]->item_number])->update([
                'success' => 1
              ]);
            }
            elseif (count($auciton)==3) {
              $data = new Enditem([
                'success_price1'=>$auciton[0]->item_price,
                'success_price2'=>$auciton[1]->item_price,
                'success_price3'=>$auciton[2]->item_price,
                'success_user1'=>$auciton[0]->buyer_ID,
                'success_user2'=>$auciton[1]->buyer_ID,
                'success_user3'=>$auciton[2]->buyer_ID,
                'success_date'=> date('Y-m-d'),
                'buyer' => $auciton[0]->buyer_ID,
                'end_num'=> $item_number[$i]->item_number
              ]);
              $data->save();
              Item::where(['item_number'=> $item_number[$i]->item_number])->update([
                'success' => 1
              ]);
            }
            elseif (count($auciton)==2) {
              $data = new Enditem([
                'success_price1'=>$auciton[0]->item_price,
                'success_price2'=>$auciton[1]->item_price,
                'success_user1'=>$auciton[0]->buyer_ID,
                'success_user2'=>$auciton[1]->buyer_ID,
                'success_date'=> date('Y-m-d'),
                'buyer' => $auciton[0]->buyer_ID,
                'end_num'=> $item_number[$i]->item_number
              ]);
              $data->save();
              Item::where(['item_number'=> $item_number[$i]->item_number])->update([
                'success' => 1
              ]);
            }
            elseif (count($auciton)==1) {
              $data = new Enditem([
                'success_price1'=>$auciton[0]->item_price,
                'success_user1'=>$auciton[0]->buyer_ID,
                'success_date'=> date('Y-m-d'),
                'buyer' => $auciton[0]->buyer_ID,
                'end_num'=> $item_number[$i]->item_number
              ]);
              $data->save();
              Item::where(['item_number'=> $item_number[$i]->item_number])->update([
                'success' => 1
              ]);
            }
            else{
              $data = new Enditem([
                'end_num'=> $item_number[$i]->item_number
              ]);
              $data->save();
            }
          }


        }
    }
}
