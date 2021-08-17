<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use App\Http\Resources\CryptoResource;

class CryptoController extends Controller
{
    public function index() {

        $portfolio = auth()->user()->portfolio;

        return Inertia::render('Crypto/Index', [
            'cryptos' => CryptoResource::collection($portfolio->cryptos),
        ]); 
    }
}
