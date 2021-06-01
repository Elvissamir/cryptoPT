<?php

namespace Tests\Feature\PortfolioCryptos;

use Tests\TestCase;
use App\Models\Crypto;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class JsonDeleteRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_removes_crypto_from_portfolio()
    {
        $this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();
        $amount = rand(1, 1000);

        $portfolio->cryptos()->attach($crypto->id, ['amount' => $amount]);

        $this->assertDatabaseHas('crypto_portfolio', [
            'crypto_id' => $crypto->id,
            'portfolio_id' => $portfolio->id,
            'amount' => $amount
        ]);

        $response = $this->actingAs($portfolio->user)->deleteJson(route('portfolioCryptos.destroy', $crypto->id));

        $this->assertDatabaseMissing('crypto_portfolio', [
            'crypto_id' => $crypto->id,
            'portfolio_id' => $portfolio->id,
            'amount' => $amount
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $crypto->id,
                'name' => $crypto->name,
                'symbol' => $crypto->symbol
            ]
        ]);
    }

   public function test_can_only_remove_cryptos_that_exist()
   {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();

        $response = $this->actingAs($portfolio->user)->deleteJson(route('portfolioCryptos.destroy', rand(1,1000)));

        $response->assertStatus(404);
   }

   public function test_guests_can_not_remove_cryptos_from_portfolio()
   {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();
        $amount = rand(1, 1000);

        $portfolio->cryptos()->attach($crypto->id, ['amount' => $amount]);

        $response = $this->deleteJson(route('portfolioCryptos.destroy', $crypto->id));

        $this->assertDatabaseHas('crypto_portfolio', [
            'crypto_id' => $crypto->id,
            'portfolio_id' => $portfolio->id,
            'amount' => $amount
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthenticated.'
        ]);
   }

   public function test_a_user_can_only_remove_cryptos_from_his_portfolio()
   {
        $this->withoutExceptionHandling();
        $crypto = Crypto::factory()->create();

        $portfolioA = $this->createPortfolio();
        $amountA = rand(1, 1000);

        $portfolioA->cryptos()->attach($crypto->id, ['amount' => $amountA]);

        $portfolioB = $this->createPortfolio();
        $amountB = rand(1, 1000);

        $portfolioB->cryptos()->attach($crypto->id, ['amount' => $amountB]);

        $response = $this->actingAs($portfolioB->user)->deleteJson(route('portfolioCryptos.destroy', $crypto->id));

        $this->assertDatabaseMissing('crypto_portfolio', [
            'crypto_id' => $crypto->id,
            'portfolio_id' => $portfolioB->id,
            'amount' => $amountB
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $crypto->id,
                'name' => $crypto->name,
                'symbol' => $crypto->symbol
            ]
        ]);
   }

   public function test_can_only_remove_existing_cryptos_from_portfolio()
   {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();
        $amount = rand(1, 1000);

        $response = $this->actingAs($portfolio->user)->deleteJson(route('portfolioCryptos.destroy', $crypto->id));

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'The crypto hasnt been added to the portfolio.'
        ]);
    }

    public function test_the_crypto_id_must_be_an_integer()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();
        $amount = rand(1, 1000);

        $portfolio->cryptos()->attach($crypto->id, ['amount' => $amount]);

        $cryptoId = Str::random(4);

        $response = $this->actingAs($portfolio->user)->deleteJson(route('portfolioCryptos.destroy', $cryptoId));

        $this->assertDatabaseHas('crypto_portfolio', [
            'crypto_id' => $crypto->id,
            'portfolio_id' => $portfolio->id,
            'amount' => $amount
        ]);

        $response->assertStatus(404);
    }
}
