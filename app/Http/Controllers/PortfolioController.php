<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PortfolioController extends Controller
{
    public function show()
    {
        $portfolio = auth()->user()->portfolio;

        $pageTitle = 'My Portfolio';
        $portfolio = [
            'created_at' => $portfolio->created_at->diffForHumans(),
            'updated_at' => $portfolio->updated_at->diffForHumans()
        ];

        return view('portfolio.show')
               ->with('pageTitle', $pageTitle)
               ->with('portfolio', $portfolio);
    }
}
