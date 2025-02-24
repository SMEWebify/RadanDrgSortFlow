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
        $currentYear = Carbon::now()->format('Y');

        // Fetch counts for different statuses
        $data['to_be_planned_count'] = DB::table('drgs')->where('statu', '=', '1')->count();
        $data['planned_count'] = DB::table('drgs')->whereIn('statu', ['2', '3'])->count();
        $data['done_count'] = DB::table('drgs')->where('statu', '=', '5')->count();
        $data['stop_count'] = DB::table('drgs')->where('statu', '=', '7')->count();

        // Fetch material data for chart
        $data['materialDataRate'] = DB::table('drgs')
            ->selectRaw('material, SUM(unit_time * sheet_qty) AS DrgTotalTime')
            ->whereYear('created_at', $currentYear)
            ->groupBy('material')
            ->get();

        // Fetch monthly data for chart
        $months = [
            'janvier' => '01', 'fevrier' => '02', 'mars' => '03', 'avril' => '04',
            'mai' => '05', 'juin' => '06', 'juillet' => '07', 'aout' => '08',
            'septembre' => '09', 'octobre' => '10', 'novembre' => '11', 'decembre' => '12'
        ];

        foreach ($months as $monthName => $monthNumber) {
            $data["{$monthName}_count"] = DB::table('drgs')
                ->select(DB::raw('SUM(sheet_qty * unit_time) as total_time'))
                ->whereMonth('created_at', $monthNumber)
                ->whereYear('created_at', $currentYear)
                ->get();
        }

        return view('home')->with('data', $data);
    }
}
