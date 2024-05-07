<?php

namespace App\Http\Controllers\Admin\Modules\CoinPrice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\CoinPrice;
use Carbon\Carbon;
use Session;
use Validator;
use File;
use Illuminate\Validation\Rule;
use App\Interfaces\CoinPriceInterface;

class CoinPriceController extends Controller
{
    private CoinPriceInterface $coinPriceRepository;
    public function __construct(CoinPriceInterface $coinPriceRepository)
    {
        $this->middleware('admin.auth:admin');
        $this->coinPriceRepository = $coinPriceRepository;
    }

    public function index(Request $request) {
        $data['coin_prices'] = $this->coinPriceRepository->getAllCoinPrice(@$request->all(), 10);
        return view('admin.modules.coin_price.index',$data);
    }

    public function create() {
        return view('admin.modules.coin_price.modify');
    }


    public function store(Request $request) {
        $validated = $request->validate([
            'plan_name' => [
                'required',
                'string',
                'max:199',
                Rule::unique('coin_prices')->where(function($query) use ($request){
                    $query->where('status','!=','D')->where('_id','!=',@$request->rowid);
                })
            ],
            'plan_coin' => [
                'required',
                'numeric',
                Rule::unique('coin_prices','from_coin')->where(function($query) use ($request){
                    $query->where('status','!=','D')->where('_id','!=',@$request->rowid);
                })
            ],
            // 'to_coin' => 'required|numeric',
            'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'sale_price' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        try {
            @$data = $this->coinPriceRepository->createCoinPrice(@$request->all());
            if(@$data){
                if(!empty(@$request->rowid)){
                    Session::flash('success','Coin price updated successfully!');
                }
                else{
                    Session::flash('success','Coin price created successfully!');
                }
            } else {
                Session::flash('error','Sorry a problem has occurred.');
            }  
        } catch(\Exception $e) {
            Session::flash('error',@$e->getMessage()); 
        }
        return redirect()->back();
    }


    public function edit($id=null) {
        try{
            $data['coin_price_data'] = $coin_price_data = $this->coinPriceRepository->getCoinPriceId(@$id);
            if(!empty(@$coin_price_data)){
                return view('admin.modules.coin_price.modify',$data);
            } else {
                Session::flash('error','Sorry a problem has occurred.');
            }
        }
        catch(\Exception $e) {
            Session::flash('error',@$e->getMessage());
        }
        return redirect()->back();
    }

    public function delete($id) {
        try{
            $coinPrice = $this->coinPriceRepository->getCoinPriceId(@$id);
            if(!empty(@$coinPrice)){
                $this->coinPriceRepository->deleteCoinPrice(@$id);
                Session::flash('success',"Coin price deleted successfully");  
            }else{
                Session::flash('error','Sorry a problem has occurred.');
            }
        }
        catch(\Exception $e) {
            Session::flash('error',@$e->getMessage());
        }
        return redirect()->back();
    }

    public function status($id) {
        try{
            $this->coinPriceRepository->statusCoinPrice(@$id);
            Session::flash('success',"Coin price status change successfully");
        }
        catch(\Exception $e) {
            Session::flash('error',@$e->getMessage());
        }
        return redirect()->back();
    }

}
