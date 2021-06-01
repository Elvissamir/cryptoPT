<?php

namespace Tests\Feature\PortfolioCryptos;

use Tests\TestCase;

use App\Models\Crypto;
use App\Http\Resources\CryptoResource;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;

class JsonGetRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_gets_list_of_cryptos_in_portfolio()
    {
        $this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();

        $cryptoA = Crypto::factory()->create();
        $cryptoB = Crypto::factory()->create();
        $cryptoC = Crypto::factory()->create();

        //dd(json_encode(new CryptoResource($cryptoA)));

        $this->attachToPortfolio($portfolio, $cryptoA->id);
        $this->attachToPortfolio($portfolio, $cryptoB->id);

        $response = $this->actingAs($portfolio->user)->getJson(route('portfolioCryptos.index'));

        //dd(json_decode($response->getContent())->data[0]->id);

        $response->assertJson([
            'data' => [
                [
                    'id' => $cryptoA->id,
                    'symbol' => $cryptoA->symbol,
                    'name' => $cryptoA->name,
                    'amount' => $portfolio->findCrypto($cryptoA->name)->pivot->amount
                ],
                [
                    'id' => $cryptoB->id,
                    'symbol' => $cryptoB->symbol,
                    'name' => $cryptoB->name,
                    'amount' => $portfolio->findCrypto($cryptoB->name)->pivot->amount
                ]
            ]
        ]);
    }

    public function test_guests_can_not_get_crypto_list()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();

        $cryptoA = Crypto::factory()->create();
        $cryptoB = Crypto::factory()->create();

        //dd(json_encode(new CryptoResource($cryptoA)));

        $this->attachToPortfolio($portfolio, $cryptoA->id);
        $this->attachToPortfolio($portfolio, $cryptoB->id);

        $response = $this->getJson(route('portfolioCryptos.index'));

        $response->assertStatus(401);
        $response->assertJson([
           'message' => 'Unauthenticated.'
        ]);
    }

    public function test_a_user_can_only_get_crypto_list_for_his_portfolio()
    {
        $this->withoutExceptionHandling();

        $portfolioA = $this->createPortfolio();

        $cryptoA = Crypto::factory()->create();
        $cryptoC = Crypto::factory()->create();
        $this->attachToPortfolio($portfolioA, $cryptoA);
        $this->attachToPortfolio($portfolioA, $cryptoC);

        $portfolioB = $this->createPortfolio();

        $cryptoB = Crypto::factory()->create();
        $this->attachToPortfolio($portfolioB, $cryptoB);

        //Owner of portfolioA can only get his cryptos data
        $response = $this->actingAs($portfolioA->user)->getJson(route('portfolioCryptos.index'));

        $response->assertJson([
            'data' => [
                [
                    'id' => $cryptoA->id,
                    'symbol' => $cryptoA->symbol,
                    'name' => $cryptoA->name,
                    'amount' => $portfolioA->findCrypto($cryptoA->name)->pivot->amount,
                ],
                [
                    'id' => $cryptoC->id,
                    'symbol' => $cryptoC->symbol,
                    'name' => $cryptoC->name,
                    'amount' => $portfolioA->findCrypto($cryptoC->name)->pivot->amount,
                ]
            ]
        ]);

        //Owner of portfolioB can only get his cryptos data
        $response = $this->actingAs($portfolioB->user)->getJson(route('portfolioCryptos.index'));

        $response->assertJson([
            'data' => [
                [
                    'id' => $cryptoB->id,
                    'symbol' => $cryptoB->symbol,
                    'name' => $cryptoB->name,
                    'amount' => $portfolioB->findCrypto($cryptoB->name)->pivot->amount,
                ],
            ]
        ]);
    }
}
