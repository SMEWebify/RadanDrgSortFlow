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

        $data['janvier_count'] = DB::table('drgs')->select(DB::raw('SUM(sheet_qty*unit_time) as total_time'))->whereMonth('created_at', '01')->whereYear('created_at', $CurentYear)->get();
        $data['fevrier_count'] = DB::table('drgs')->select(DB::raw('SUM(sheet_qty*unit_time) as total_time'))->whereMonth('created_at', '02')->whereYear('created_at', $CurentYear)->get();
        $data['mars_count'] = DB::table('drgs')->select(DB::raw('SUM(sheet_qty*unit_time) as total_time'))->whereMonth('created_at', '03')->whereYear('created_at', $CurentYear)->get();
        $data['avril_count'] = DB::table('drgs')->select(DB::raw('SUM(sheet_qty*unit_time) as total_time'))->whereMonth('created_at', '04')->whereYear('created_at', $CurentYear)->get();
        $data['mai_count'] = DB::table('drgs')->select(DB::raw('SUM(sheet_qty*unit_time) as total_time'))->whereMonth('created_at', '05')->whereYear('created_at', $CurentYear)->get();
        $data['juin_count'] = DB::table('drgs')->select(DB::raw('SUM(sheet_qty*unit_time) as total_time'))->whereMonth('created_at', '06')->whereYear('created_at', $CurentYear)->get();
        $data['juillet_count'] = DB::table('drgs')->select(DB::raw('SUM(sheet_qty*unit_time) as total_time'))->whereMonth('created_at', '07')->whereYear('created_at', $CurentYear)->get();
        $data['aout_count'] = DB::table('drgs')->select(DB::raw('SUM(sheet_qty*unit_time) as total_time'))->whereMonth('created_at', '08')->whereYear('created_at', $CurentYear)->get();
        $data['septembre_count'] = DB::table('drgs')->select(DB::raw('SUM(sheet_qty*unit_time) as total_time'))->whereMonth('created_at', '09')->whereYear('created_at', $CurentYear)->get();
        $data['octobre_count'] = DB::table('drgs')->select(DB::raw('SUM(sheet_qty*unit_time) as total_time'))->whereMonth('created_at', '10')->whereYear('created_at', $CurentYear)->get();
        $data['novembre_count'] = DB::table('drgs')->select(DB::raw('SUM(sheet_qty*unit_time) as total_time'))->whereMonth('created_at', '11')->whereYear('created_at', $CurentYear)->get();
        $data['decembre_count'] = DB::table('drgs')->select(DB::raw('SUM(sheet_qty*unit_time) as total_time'))->whereMonth('created_at', '12')->whereYear('created_at', $CurentYear)->get();

        return view('home')->with('data',$data);
    }
}
