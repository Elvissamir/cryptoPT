<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Crypto;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class DemoUserLoginController extends Controller
{
    // Store a new random demo user
    public function store ()
    {
        $demoUser = User::factory()->create(['name' => 'Demo']);

        event(new Registered($demoUser));

        // ADD CRYPTOS TO DEMO USER'S PORTFOLIO
        $cryptoA = Crypto::where('cg_id', 'bitcoin')->get()->toArray();
        $cryptoB = Crypto::where('cg_id', 'theta-token')->get()->toArray();
        $cryptoC = Crypto::where('cg_id', 'theta-fuel')->get()->toArray();
        $cryptoD = Crypto::where('cg_id', 'litecoin')->get()->toArray();
        $cryptoE = Crypto::where('cg_id', 'ethereum')->get()->toArray();

        $demoUser->portfolio->addCrypto($cryptoA[0]['id'], 1);
        $demoUser->portfolio->addCrypto($cryptoB[0]['id'], 100);
        $demoUser->portfolio->addCrypto($cryptoC[0]['id'], 100);
        $demoUser->portfolio->addCrypto($cryptoD[0]['id'], 1);
        $demoUser->portfolio->addCrypto($cryptoE[0]['id'], 1);

        Auth::login($demoUser);

        return redirect(route('portfolioCryptos.show'));
    }
}
