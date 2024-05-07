<?php

namespace App\Repositories;

use App\Interfaces\CoinPriceInterface;
use App\Models\CoinPrice;

class CoinPriceRepository implements CoinPriceInterface 
{
    public function getAllCoinPrice($request, $limit) 
    {
        $CoinPrice = CoinPrice::orderBy('from_coin','asc')->where('status','!=','D');
        if(!empty(@$request['keyword'])){
            $CoinPrice = @$CoinPrice->where(function($query) use ($request){
                $query->where('from_coin', 'Like', '%' . @$request['keyword'] .'%')
                ->orWhere('to_coin', 'Like', '%' . @$request['keyword'] .'%')
                ->orWhere('price', 'Like', '%' . @$request['keyword'] .'%');
            });
        }
        return $CoinPrice->paginate($limit);
    }

    public function getCoinPriceId($coinPriceId) 
    {
        return CoinPrice::where('_id',@$coinPriceId)->where('status','!=','D')->first();
    }

    public function deleteCoinPrice($coinPriceId) 
    {
        CoinPrice::where('_id',@$coinPriceId)->update(['status'=>'D']);
    }

    public function statusCoinPrice($coinPriceId) 
    {
        $data = CoinPrice::where('_id',@$coinPriceId)->first();
        if($data->status=='A') {
            $data->update(['status' => 'I']);
        } else {
            $data->update(['status' => 'A']);
        }
        return $data;
    }

    public function createCoinPrice(array $request) 
    {
        $new['plan_name'] = @$request['plan_name'];
        $new['from_coin'] = floatval(@$request['plan_coin']);
        $new['price'] = floatval(@$request['price']);
        $new['sale_price'] = @$request['sale_price'] ? floatval(@$request['sale_price']) : floatval(0.00);
        $new['status'] = 'A';
        $data = CoinPrice::updateOrCreate(['_id' => @$request['rowid']], $new);
        return $data;
    }
}