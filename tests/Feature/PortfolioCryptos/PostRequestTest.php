<?php

namespace Tests\Feature\PortfolioCryptos;

use Tests\TestCase;
use App\Models\Crypto;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;

class PostRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_adds_crypto_to_portfolio()
    {
        $this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();

        $amount = rand(1, 1000);

        $response = $this->actingAs($portfolio->user)
                         ->from(route('portfolioCryptos.show'))
                         ->post(route('portfolioCryptos.store'), [
            'cg_id' => $crypto->cg_id,
            'amount' => $amount,
        ]);

        $this->assertDatabaseHas('crypto_portfolio', [
            'crypto_id' => $crypto->id,
            'portfolio_id' => $portfolio->id,
            'amount' => $amount
        ]);

        $response->assertRedirect(route('portfolioCryptos.show'));
        $response->assertSessionHas('success', $crypto->cg_id.' has been added to your portfolio.');
    }

    public function test_guests_can_not_add_cryptos_to_portfolio()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();
        $amount = rand(1, 1000);

        $response = $this->post(route('portfolioCryptos.store'), [
            'id' => $crypto->id,
            'amount' => $amount,
        ]);

        $this->assertDatabaseMissing('crypto_portfolio', [
            'crypto_id' => $crypto->id,
            'portfolio_id' => $portfolio->id,
            'amount' => $amount
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_a_user_can_only_add_cryptos_to_his_portfolio()
    {
        $this->withoutExceptionHandling();

        $portfolioA = $this->createPortfolio();
        $cryptoA = Crypto::factory()->create();
        $amountA = rand(1, 1000);

        $portfolioB = $this->createPortfolio();
        $cryptoB = Crypto::factory()->create();
        $amountB = rand(1, 1000);

        $response = $this->actingAs($portfolioA->user)->post(route('portfolioCryptos.store'), [
            'cg_id' => $cryptoA->cg_id,
            'amount' => $amountA
        ]);

        $this->assertDatabaseMissing('crypto_portfolio', [
            'crypto_id' => $cryptoA->id,
            'portfolio_id' => $portfolioB->id,
            'amount' => $amountA
        ]);

        $response = $this->actingAs($portfolioB->user)->post(route('portfolioCryptos.store'), [
            'cg_id' => $cryptoB->cg_id,
            'amount' => $amountB
        ]);

        $this->assertDatabaseMissing('crypto_portfolio', [
            'crypto_id' => $cryptoB->id,
            'portfolio_id' => $portfolioA->id,
            'amount' => $amountB
        ]);
    }

    public function test_user_can_only_add_existing_cryptos_to_portfolio()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $amount = rand(1, 1000);
        $randomId = Str::random(4);

        $response = $this->actingAs($portfolio->user)->post(route('portfolioCryptos.store'), [
            'cg_id' => $randomId,
            'amount' => $amount,
        ]);

        $this->assertDatabaseMissing('crypto_portfolio', [
            'crypto_id' => $randomId,
            'portfolio_id' => $portfolio->id,
            'amount' => $amount
        ]);

        $response->assertStatus(404);
    }

    public function test_can_not_add_crypto_to_portfolio_if_it_has_already_been_added()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();
        $amount = rand(1, 1000);

        $this->attachToPortfolio($portfolio, $crypto->id);

        $response = $this->actingAs($portfolio->user)->post(route('portfolioCryptos.store'), [
            'cg_id' => $crypto->cg_id,
            'amount' => $amount,
        ]);

        $this->assertDatabaseMissing('crypto_portfolio', [
            'crypto_id' => $crypto->id,
            'portfolio_id' => $portfolio->id,
            'amount' => $amount
        ]);

        $response->assertStatus(409);
    }


    public function test_cg_id_of_crypto_to_add_is_required()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();
        $amount = rand(1, 1000);

        $response = $this->actingAs($portfolio->user)
                         ->from(route('portfolioCryptos.show'))
                         ->post(route('portfolioCryptos.store'), [
            'amount' => $amount,
        ]);

        $this->assertDatabaseCount('crypto_portfolio', 0);

        $response->assertRedirect();
    }

    public function test_cg_id_must_be_a_string()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();
        $amount = rand(1, 1000);

        $response = $this->actingAs($portfolio->user)->post(route('portfolioCryptos.store'), [
            'cg_id' => 1,
            'amount' => $amount,
        ]);

        $this->assertDatabaseCount('crypto_portfolio', 0);

        $response->assertRedirect();
    }

    public function test_amount_of_crypto_to_add_is_required()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();
 
        $response = $this->actingAs($portfolio->user)->post(route('portfolioCryptos.store'), [
            'id' => $crypto->id,
        ]);
 
        $this->assertDatabaseCount('crypto_portfolio', 0);
 
        $response->assertRedirect();
    }

    public function test_amount_of_crypto_to_add_must_be_a_number()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();
        $amount = "non numeric";

        $response = $this->actingAs($portfolio->user)->post(route('portfolioCryptos.store'), [
            'id' => $crypto->id,
            'amount' => $amount,
        ]);

        $this->assertDatabaseCount('crypto_portfolio', 0);

        $response->assertRedirect();
    }

    public function test_min_amount_of_crypto_to_add_is_one_satoshi()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();
        //RIght amount is 0.00000001
        $amount = 0.000000001;

        $response = $this->actingAs($portfolio->user)->post(route('portfolioCryptos.store'), [
            'id' => $crypto->id,
            'amount' => $amount,
        ]);

        $this->assertDatabaseCount('crypto_portfolio', 0);

        $response->assertRedirect();
    }

    public function test_max_amount_of_crypto_to_add_is_ten_million()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();
        //Right amount is 10 000 000
        $amount = 10000001;

        $response = $this->actingAs($portfolio->user)->post(route('portfolioCryptos.store'), [
            'id' => $crypto->id,
            'amount' => $amount,
        ]);

        $this->assertDatabaseCount('crypto_portfolio', 0);

        $response->assertRedirect();
    }

}

