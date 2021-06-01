<?php

namespace Tests;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    //Helper Functions
    public function createPortfolio()
    {
        $user = User::factory()->create();
 
        $portfolio = Portfolio::factory()->create([
            'user_id' => $user->fresh()->id,
        ]);
 
        return $portfolio;
     }
 
    public function attachToPortfolio($portfolio, $cryptoId)
    {
        $portfolio->cryptos()->attach($cryptoId, ['amount' => rand(1, 1000)]);
    }
}
