<?php

namespace Tests\Feature\PortfolioCryptos;

use Tests\TestCase;
use App\Models\Crypto;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PutRequestTest extends TestCase
{
    //use RefreshDatabase;
    use RefreshDatabase;

    public function test_updates_crypto_amount_in_portfolio()
    {
        $this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();
        $initialAmount = rand(1, 1000);

        $portfolio->cryptos()->attach($crypto->id, ['amount' => $initialAmount]);

        $this->assertDatabaseHas('crypto_portfolio', [
            'crypto_id' => $crypto->id,
            'portfolio_id' => $portfolio->id,
            'amount' => $initialAmount
        ]);

        $updatedAmount = rand(1, 1000);

        $response = $this->actingAs($portfolio->user)
                         ->from(route('portfolioCryptos.show'))
                         ->put(route('portfolioCryptos.update', $crypto->cg_id), [
            'amount' => $updatedAmount,
        ]);

        $this->assertDatabaseHas('crypto_portfolio', [
            'crypto_id' => $crypto->id,
            'portfolio_id' => $portfolio->id,
            'amount' => $updatedAmount
        ]);

        $response->assertRedirect(route('portfolioCryptos.show'));
    }

    public function test_can_only_update_amount_of_cryptos_that_exist()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $randomId = Str::random(5);
        $initialAmount = rand(1, 1000);
        $updatedAmount = 100;

        $response = $this->actingAs($portfolio->user)->put(route('portfolioCryptos.update', $randomId), [
            'amount' => $updatedAmount,
        ]);

        $this->assertDatabaseMissing('crypto_portfolio', [
            'crypto_id' => $randomId,
            'portfolio_id' => $portfolio->id,
            'amount' => $initialAmount
        ]);

        $response->assertStatus(404);
    }

    public function test_can_only_update_amount_of_cryptos_that_are_already_in_portfolio()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();
        $updateAmount = rand(1, 1000);

        $response = $this->actingAs($portfolio->user)->put(route('portfolioCryptos.update', $crypto->cg_id), [
            'amount' => $updateAmount
        ]);

        $this->assertDatabaseMissing('crypto_portfolio', [
            'crypto_id' => $crypto->id,
            'portfolio_id' => $portfolio->id,
            'amount' => $updateAmount
        ]);

        $response->assertStatus(404);
    }

    public function test_guests_can_not_update_crypto_amount_in_portfolio()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();

        $portfolio->cryptos()->attach($crypto->id, ['amount' => rand(1, 1000)]);

        $updatedAmount = rand(1, 1000);

        $response = $this->put(route('portfolioCryptos.update', $crypto->cg_id), [
            'amount' => $updatedAmount,
        ]);

        $this->assertDatabaseMissing('crypto_portfolio', [
            'crypto_id' => $crypto->id,
            'portfolio_id' => $portfolio->id,
            'amount' => $updatedAmount
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_a_user_can_only_update_crypto_amount_in_his_portfolio()
    {
        $this->withoutExceptionHandling();

        $crypto = Crypto::factory()->create();

        // Two portfolios have the same crypto but different amounts
        $portfolioA = $this->createPortfolio();
        $initialAmountA = rand(1, 1000);

        $portfolioA->cryptos()->attach($crypto->id, ['amount' => $initialAmountA]);

        $portfolioB = $this->createPortfolio();
        $initialAmountB = rand(1, 1000);

        $portfolioB->cryptos()->attach($crypto->id, ['amount' => $initialAmountB]);

        $updatedAmount = rand(1, 1000);

        // The owner of portfolio B can only update the amount of crypto in his portfolio
        $response = $this->actingAs($portfolioB->user)->put(route('portfolioCryptos.update', $crypto->cg_id), [
            'amount' => $updatedAmount,
        ]);

        $this->assertDatabaseHas('crypto_portfolio', [
            'crypto_id' => $crypto->id,
            'portfolio_id' => $portfolioB->id,
            'amount' => $updatedAmount
        ]);

        $response->assertRedirect();
    }

    public function test_id_of_crypto_to_update_is_required()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();
        $initialAmount = rand(1, 1000);

        $portfolio->cryptos()->attach($crypto->id, ['amount' => $initialAmount]);

        $updatedAmount = rand(1, 1000);

        $response = $this->actingAs($portfolio->user)->put(route('portfolioCryptos.update', ''), [
            'amount' => $updatedAmount,
        ]);

        $this->assertDatabaseMissing('crypto_portfolio', [
            'crypto_id' => $crypto->id,
            'portfolio_id' => $portfolio->id,
            'amount' => $updatedAmount
        ]);

        $response->assertStatus(405);
        //$response->assertRedirect(route('portfolioCryptos.show'));
    }

    public function test_id_of_crypto_must_be_a_string()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();
        $initialAmount = rand(1, 1000);

        $portfolio->cryptos()->attach($crypto->id, ['amount' => $initialAmount]);

        $updatedAmount = rand(1, 1000);

        $response = $this->actingAs($portfolio->user)->put(route('portfolioCryptos.update', Str::random(4)), [
            'amount' => $updatedAmount,
        ]);

        $this->assertDatabaseMissing('crypto_portfolio', [
            'crypto_id' => $crypto->id,
            'portfolio_id' => $portfolio->id,
            'amount' => $updatedAmount
        ]);

        $response->assertStatus(404);
    }

    public function test_amount_of_crypto_to_update_is_required()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();
        $initialAmount = rand(1, 1000);

        $portfolio->cryptos()->attach($crypto->id, ['amount' => $initialAmount]);

        $updatedAmount = rand(1, 1000);

        $response = $this->actingAs($portfolio->user)->put(route('portfolioCryptos.update', $crypto->cg_id), []);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['amount']);
    }

    public function test_amount_of_crypto_to_update_must_be_a_number()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();
        $initialAmount = rand(1, 1000);

        $portfolio->cryptos()->attach($crypto->id, ['amount' => $initialAmount]);

        $updatedAmount = Str::random(4);

        $response = $this->actingAs($portfolio->user)->put(route('portfolioCryptos.update', $crypto->cg_id), [
            'amount' => $updatedAmount,
        ]);

        $this->assertDatabaseHas('crypto_portfolio', [
            'crypto_id' => $crypto->id,
            'portfolio_id' => $portfolio->id,
            'amount' => $initialAmount
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['amount']);
    }

    public function test_min_amount_to_update_is_one_satoshi()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();
        $initialAmount = rand(1, 1000);

        $portfolio->cryptos()->attach($crypto->id, ['amount' => $initialAmount]);

        $updatedAmount = 0.000000001;

        $response = $this->actingAs($portfolio->user)->put(route('portfolioCryptos.update', $crypto->cg_id), [
            'amount' => $updatedAmount,
        ]);

        $this->assertDatabaseHas('crypto_portfolio', [
            'crypto_id' => $crypto->id,
            'portfolio_id' => $portfolio->id,
            'amount' => $initialAmount
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['amount']);
    }

    public function test_max_amount_to_update_is_ten_million()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();
        $initialAmount = rand(1, 1000);
 
        $portfolio->cryptos()->attach($crypto->id, ['amount' => $initialAmount]);
 
        $updatedAmount = 10000001;
 
        $response = $this->actingAs($portfolio->user)->put(route('portfolioCryptos.update', $crypto->cg_id), [
            'amount' => $updatedAmount,
        ]);
 
        $this->assertDatabaseHas('crypto_portfolio', [
            'crypto_id' => $crypto->id,
            'portfolio_id' => $portfolio->id,
            'amount' => $initialAmount
        ]);
 
        $response->assertRedirect();
        $response->assertSessionHasErrors(['amount']);
    }
}
