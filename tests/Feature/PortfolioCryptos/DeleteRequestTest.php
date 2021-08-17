<?php

namespace Tests\Feature\PortfolioCryptos;

use Tests\TestCase;
use App\Models\Crypto;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DeleteRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_removes_crypto_from_portfolio()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();
        $amount = rand(1, 1000);

        $portfolio->cryptos()->attach($crypto->id, ['amount' => $amount]);

        $this->assertDatabaseHas('crypto_portfolio', [
            'crypto_id' => $crypto->id,
            'portfolio_id' => $portfolio->id,
            'amount' => $amount
        ]);

        $response = $this->actingAs($portfolio->user)
                         ->from(route('portfolioCryptos.show'))
                         ->delete(route('portfolioCryptos.destroy', $crypto->cg_id));

        $this->assertDatabaseMissing('crypto_portfolio', [
            'crypto_id' => $crypto->id,
            'portfolio_id' => $portfolio->id,
            'amount' => $amount
        ]);

        $response->assertRedirect(route('portfolioCryptos.show'));
        // $response->assertSessionHas('success', $crypto->cg_id.' was removed from your portfolio.');
    }

   public function test_can_only_remove_cryptos_that_exist()
   {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();

        $response = $this->actingAs($portfolio->user)
                         ->deleteJson(route('portfolioCryptos.destroy', rand(1,1000)));

        $response->assertStatus(404);
   }

   public function test_guests_can_not_remove_cryptos_from_portfolio()
   {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();
        $amount = rand(1, 1000);

        $portfolio->cryptos()->attach($crypto->id, ['amount' => $amount]);

        $response = $this->delete(route('portfolioCryptos.destroy', $crypto->cg_id));

        $this->assertDatabaseHas('crypto_portfolio', [
            'crypto_id' => $crypto->id,
            'portfolio_id' => $portfolio->id,
            'amount' => $amount
        ]);

        $response->assertRedirect(route('login'));
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

        $response = $this->actingAs($portfolioB->user)
                         ->delete(route('portfolioCryptos.destroy', $crypto->cg_id));

        $this->assertDatabaseMissing('crypto_portfolio', [
            'crypto_id' => $crypto->id,
            'portfolio_id' => $portfolioB->id,
            'amount' => $amountB
        ]);
   }

   public function test_can_only_remove_existing_cryptos_from_portfolio()
   {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();

        $cryptoA = Crypto::factory()->create();
        $cryptoB = Crypto::factory()->create();

        $this->attachToPortfolio($portfolio, $cryptoA->id);

        $response = $this->actingAs($portfolio->user)->delete(route('portfolioCryptos.destroy', $cryptoB->cg_id));

        $response->assertStatus(404);
    }

}
