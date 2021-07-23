<?php

namespace Tests\Feature\PortfolioCryptos;

use App\Http\Resources\CryptoResource;
use App\Models\Crypto;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\Assert;
use Tests\TestCase;

class GetPortfolioTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_page_has_the_portfolio_show_component()
    {
        $this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();

        $response = $this->actingAs($portfolio->user)->get(route('portfolioCryptos.show'));

        $response->assertInertia(function (Assert $page) {
            $page->component('Portfolio/Show');
        });
    }

    public function test_portfolio_show_component_has_the_crypto_list()
    {
        $this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();

        $cryptoA = Crypto::factory()->create();
        $cryptoB = Crypto::factory()->create();
        $cryptoC = Crypto::factory()->create();

        $this->attachToPortfolio($portfolio, $cryptoA->id);
        $this->attachToPortfolio($portfolio, $cryptoB->id);

        $response = $this->actingAs($portfolio->user)->get(route('portfolioCryptos.show'));

        $response->assertInertia(fn (Assert $page) => 
            $page->has('cryptos')
                 ->where('cryptos', CryptoResource::collection($portfolio->cryptos))
        );
    }

    public function test_portfolio_show_component_has_the_portfolio_created_and_updated_at_data()
    {
        $this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();

        $portfolio->created_at = Carbon::now()->subDays(1);
        $portfolio->save();

        $cryptoA = Crypto::factory()->create();
        $cryptoB = Crypto::factory()->create();
        $cryptoC = Crypto::factory()->create();

        $this->attachToPortfolio($portfolio, $cryptoA->id);
        $this->attachToPortfolio($portfolio, $cryptoB->id);

        $response = $this->actingAs($portfolio->user)->get(route('portfolioCryptos.show'));

        $response->assertInertia(fn (Assert $page) => 
            $page->has('cryptos')
                 ->where('portfolio.created_at', $portfolio->created_at->diffForHumans())
                 ->where('portfolio.updated_at', $portfolio->updated_at->diffForHumans())
        );
    }

    public function test_guests_can_not_see_the_portfolio_page()
    {
        //$this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();

        $cryptoA = Crypto::factory()->create();
        $cryptoB = Crypto::factory()->create();

        $response = $this->get(route('portfolioCryptos.show'));

        $response->assertRedirect(route('login'));
    }

    public function test_a_user_can_only_see_his_portfolio()
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
        $response = $this->actingAs($portfolioA->user)->get(route('portfolioCryptos.show'));

        $response->assertInertia(fn (Assert $page) => 
            $page->has('cryptos')
                 ->where('cryptos', CryptoResource::collection($portfolioA->cryptos))
        );

        //Owner of portfolioB can only get his cryptos data
        $response = $this->actingAs($portfolioB->user)->get(route('portfolioCryptos.show'));

        $response->assertInertia(fn (Assert $page) => 
            $page->has('cryptos')
                 ->where('cryptos', CryptoResource::collection($portfolioB->cryptos))
        );
    }
}
