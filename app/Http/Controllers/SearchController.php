<?php

namespace App\Http\Controllers;

use App\Models\Drg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;

class SearchController extends Controller
{
/**
     * Show the navbar search results.
     *
     * @param Request $request
     * @return View
     */
    public function showNavbarSearchResults(Request $request)
    {
        // Check that the search keyword is present.

        if (! $request->filled('searchVal')) {
            return back();
        }

        // Get the search keyword.

        $keyword = $request->input('searchVal');

        Log::info("A navbar search was triggered with next keyword => {$keyword}");

        $results = Search::add(Drg::class, ['drg_name'])
                        ->beginWithWildcard()
                        ->orderByModel([
                            Drg::class
                        ])
                        ->get($keyword);

        return view('search', compact('results', 'keyword'));
        
    }
}