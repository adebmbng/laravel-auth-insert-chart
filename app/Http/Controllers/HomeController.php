<?php

namespace App\Http\Controllers;

use App\Charts\DailyTrx;
use App\Goods;
use App\MarketTransactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data['goods'] = Goods::all();
        Log::info(json_encode($data));

        $chart = new DailyTrx();
        $dataq = MarketTransactions::whereDate('created_at', Carbon::today())->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('h');
            })->toArray();
        $datakey = array_keys($dataq);

        $sumData = [];
        foreach ($dataq as $d){
            $sum =0;
            foreach ($d as $sing){
                $sum += $sing['quantity'];
            }
            array_push($sumData, $sum);
        }

        $chart->labels($datakey);
        $chart->dataset('daily report', 'line', $sumData);


        $seven_date = \Carbon\Carbon::today()->subDays(7);
        $trx = MarketTransactions::whereDate('created_at', '>=', $seven_date)->get()
            ->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('d');
        })->toArray();
        $weekly = new DailyTrx();
        $trxkey = array_keys($trx);
        $sumTrx = [];
        foreach ($trx as $d){
            $sum =0;
            foreach ($d as $sing){
                $sum += $sing['total'];
            }
            array_push($sumTrx, $sum);
        }
        $weekly->labels($trxkey);
        $weekly->dataset('weekly report', 'line', $sumTrx);

        Log::info(json_encode($trx));


        Log::info(json_encode($sumData));
        return view('home')->with(['data'=>$data, 'chart'=>$chart, 'weekly' => $weekly]);
    }

    public function yesterday(){
        $data['goods'] = Goods::all();
        Log::info(json_encode($data));

        $chart = new DailyTrx();
        $dataq = MarketTransactions::whereDate('created_at', Carbon::yesterday())->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('h');
            })->toArray();
        $datakey = array_keys($dataq);

        $sumData = [];
        foreach ($dataq as $d){
            $sum =0;
            foreach ($d as $sing){
                $sum += $sing['quantity'];
            }
            array_push($sumData, $sum);
        }

        $chart->labels($datakey);
        $chart->dataset('daily report', 'line', $sumData);


        $seven_date = \Carbon\Carbon::today()->subDays(7);
        $trx = MarketTransactions::whereDate('created_at', '>=', $seven_date)->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('d');
            })->toArray();
        $weekly = new DailyTrx();
        $trxkey = array_keys($trx);
        $sumTrx = [];
        foreach ($trx as $d){
            $sum =0;
            foreach ($d as $sing){
                $sum += $sing['total'];
            }
            array_push($sumTrx, $sum);
        }
        $weekly->labels($trxkey);
        $weekly->dataset('weekly report', 'line', $sumTrx);

//        Log::info(json_encode($trx));


        return view('home')->with(['data'=>$data, 'chart'=>$chart, 'weekly' => $weekly]);

    }

    public function submit(Request $request)
    {
        Log::info($request->totalPrice);
        $market = new MarketTransactions();
        $market->goods = $request->goods;
        $market->quantity = $request->qty;
        $market->total = $request->totalPrice;
        $market->price = (1000 * $request->totalPrice) / $request->qty;
        $market->user = Auth::id();
        $market->save();

        $good = Goods::whereCode($request->goods)->first();
        $good->stock = $good->stock - $request->qty;
        $good->save();

        return redirect()->route('home');

    }


}
