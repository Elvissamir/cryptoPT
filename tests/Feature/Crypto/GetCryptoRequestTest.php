<?php

namespace Tests\Feature\Crypto;

use Tests\TestCase;
use App\Models\Crypto;
use Inertia\Testing\Assert;
use App\Http\Resources\CryptoResource;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetCryptoRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_component_for_index_route_exists()
    {
        $this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();

        $response = $this->actingAs($portfolio->user)->get(route('cryptos.index'));

        $response->assertInertia(fn (Assert $page) => 
            $page->component('Crypto/Index')
        );

        $response->assertStatus(200);
    }

    public function test_only_an_authenticated_user_can_see_the_index()
    {
        // $this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();

        $response = $this->get(route('cryptos.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_the_index_component_has_data_of_all_cryptos_in_current_auth_user_portfolio()
    {
        $this->withoutExceptionHandling();

        $portfolioA = $this->createPortfolio();
        $portfolioB = $this->createPortfolio();

        $cryptoA = Crypto::factory()->create();
        $cryptoB = Crypto::factory()->create();

        $this->attachToPortfolio($portfolioA, $cryptoA->id);
        $this->attachToPortfolio($portfolioA, $cryptoB->id);

        $this->attachToPortfolio($portfolioB, $cryptoA->id);
        
        $response = $this->actingAs($portfolioB->user)->get(route('cryptos.index'));

        $response->assertInertia(fn (Assert $page) => 
        $page->has('cryptos')
             ->where('cryptos', CryptoResource::collection($portfolioB->cryptos))
        );
    }

    public function test_component_for_show_route_exists()
    {
        $this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();

        $crypto = Crypto::factory()->create();

        $response = $this->actingAs($portfolio->user)->get(route('cryptos.show', $crypto->cg_id));

        $response->assertInertia(fn (Assert $page) => 
            $page->component('Crypto/Show')
        );

        $response->assertStatus(200);
    }

    public function test_only_an_authenticated_user_can_see_the_show_route()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        $crypto = Crypto::factory()->create();

        $response = $this->get(route('cryptos.show', $crypto->cg_id));

        $response->assertRedirect(route('login'));
    }

   
    public function test_show_component_has_data_of_the_crypto()
    {
        $this->withoutExceptionHandling();

        $portfolioA = $this->createPortfolio();
        $portfolioB = $this->createPortfolio();

        $cryptoA = Crypto::factory()->create();
        $cryptoB = Crypto::factory()->create();

        $this->attachToPortfolio($portfolioA, $cryptoA->id);
        $this->attachToPortfolio($portfolioB, $cryptoB->id);
        
        $response = $this->actingAs($portfolioB->user)->get(route('cryptos.show', $cryptoB->cg_id));
        
        $cryptoBData = $portfolioB->findCrypto($cryptoB->cg_id);
        
        $response->assertInertia(fn (Assert $page) => 
            $page->has('crypto')
                ->where('crypto', new CryptoResource($cryptoBData))
                ->where('crypto.amount', $cryptoBData->pivot->amount)
        );
    }


    public function test_show_component_has_data_of_the_crypto_if_the_crypto_is_not_in_portfolio()
    {
        $this->withoutExceptionHandling();

        $portfolioA = $this->createPortfolio();

        $cryptoA = Crypto::factory()->create();
        $cryptoB = Crypto::factory()->create();

        $this->attachToPortfolio($portfolioA, $cryptoA->id);
        
        $response = $this->actingAs($portfolioA->user)->get(route('cryptos.show', $cryptoB->cg_id));
        
        $response->assertInertia(fn (Assert $page) => 
            $page->has('crypto')
                ->where('crypto', new CryptoResource($cryptoB))
                ->missing('crypto.amount')
        );
    }

}
