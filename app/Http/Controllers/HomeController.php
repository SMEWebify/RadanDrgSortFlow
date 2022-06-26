<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $CurentYear = Carbon::now()->format('Y');
        
        $data['to_be_planned_count'] = DB::table('drgs')->where('statu', '=', '1')->count();
        $data['planned_count'] = DB::table('drgs')->where('statu', '=', '2')->Orwhere('statu', '=', '3')->count();
        $data['done_count'] = DB::table('drgs')->where('statu', '=', '5')->count();
        $data['stop_count'] = DB::table('drgs')->where('statu', '=', '7')->count();

        //Quote data for chart
        $data['materialDataRate'] = DB::table('drgs')
                                    ->selectRaw('material,  SUM(unit_time * sheet_qty) AS DrgTotalTime' )
                                    ->whereYear('created_at', $CurentYear)
                                    ->groupByRaw('material')
                                    ->get();

        return view('home')->with('data',$data);
    }
}
