<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Crypto;
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

    public function show(Crypto $crypto) {

        $cryptoData = auth()->user()->portfolio->findCrypto($crypto->cg_id);
        
        if (!is_null($cryptoData))
            return Inertia::render('Crypto/Show', ['crypto' => new CryptoResource($cryptoData)]); 
        
        return Inertia::render('Crypto/Show', ['crypto' => new CryptoResource($crypto)]); 
    }
}
