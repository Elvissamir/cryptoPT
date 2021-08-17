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

    public function test_view_for_index_route_exists()
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

    public function test_the_component_has_data_of_all_cryptos_in_current_auth_user_portfolio()
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


}
