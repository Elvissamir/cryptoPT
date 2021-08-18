<?php

namespace Tests\Feature\Crypto;

use Tests\TestCase;
use App\Models\Crypto;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CryptoModelTest extends TestCase
{
    use RefreshDatabase;
   
    public function test_a_crypto_belongs_to_many_portfolios()
    {
        $this->withoutExceptionHandling();

        $portfolioA = $this->createPortfolio();
        $portfolioB = $this->createPortfolio();

        $crypto = Crypto::factory()->create();
        $this->attachToPortfolio($portfolioB, $crypto->id);

        $this->assertEquals($portfolioB->id, $crypto->portfolios()->first()->id);
    }
}
