<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DRGController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function planned()
    {
        return view('planned');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function tobeplanned()
    {
        return view('tobeplanned');
    }

        /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function cut()
    {
        return view('cut');
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function delete()
    {
        return view('delete');
    }

    
}
