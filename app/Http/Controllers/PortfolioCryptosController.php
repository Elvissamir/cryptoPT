<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCryptoInPortfolioRequest;
use App\Http\Requests\UpdateCryptoInPortfolioRequest;
use App\Http\Resources\CryptoResource;
use App\Models\Crypto;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PortfolioCryptosController extends Controller
{
    //Handling methods 
    public function show() { 

        $portfolio = auth()->user()->portfolio;

        $json = [];

        return Inertia::render('Portfolio/Show', [
            'portfolio' => [
                'created_at' => $portfolio->created_at->diffForHumans(),
                'updated_at' => $portfolio->updated_at->diffForHumans()
            ],
            'cryptos' => CryptoResource::collection($portfolio->cryptos),
        ]); 
    }

    public function store(StoreCryptoInPortfolioRequest $request)
    {
        $portfolio = auth()->user()->portfolio;

        $crypto = Crypto::findOrFail($request->id);

        abort_if($portfolio->hasCrypto($crypto->name), 409);

        $portfolio->addCrypto($crypto->id, $request->amount);

        return redirect()->back()->with('success', $crypto->name.' has been added to your portfolio.');
    }

    public function update(Crypto $crypto, UpdateCryptoInPortfolioRequest $request)
    {
        $portfolio = auth()->user()->portfolio;

        abort_if(! $portfolio->hasCrypto($crypto->name), 404);

        $portfolio->updateCryptoAmount($crypto->id, $request->amount);

        return back();
    }

    public function destroy(Crypto $crypto)
    {
        $portfolio = auth()->user()->portfolio;

        abort_if(! $portfolio->hasCrypto($crypto->name), 404);

        $portfolio->removeCrypto($crypto->id);

        return redirect()->back()->with('success', $crypto->name.' was removed from your portfolio.');
    }
}
