<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Crypto;
use Tests\DuskTestCase;
use App\Models\Portfolio;
use Laravel\Dusk\Browser;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PortfolioPageTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_page_structure_has_all_sections()
    {
        $portfolio = $this->createPortfolio();

        $this->browse(function (Browser $browser) use($portfolio) {
            $browser->loginAs($portfolio->user)
                    ->visit(route('portfolio.show'))
                    ->assertSee('My Portfolio')
                    ->assertSee('My Cryptos')
                    ->assertSee('Portfolio Crypto Diversification')
                    ->assertSee('Cryptos Performance');
        });
    }

    public function test_shows_created_at_date_in_human_format()
    {
        $portfolio = $this->createPortfolio();
        $portfolio->created_at = Carbon::now()->subDays(1);
        $portfolio->save();

        $this->browse(function (Browser $browser) use($portfolio) {
            $browser->loginAs($portfolio->user)
                    ->visit(route('portfolio.show'))
                    ->assertSee('Created ' . $portfolio->created_at->diffForHumans());
        });
    }

    public function test_shows_cryptos_list()
    {
        $portfolio = $this->createPortfolio();

        $cryptoA = Crypto::factory()->create();
        $cryptoB = Crypto::factory()->create();

        $this->attachToPortfolio($portfolio, $cryptoA);
        $this->attachToPortfolio($portfolio, $cryptoB);

        /*

        $this->browse(function (Browser $browser) use($portfolio, $cryptoA, $cryptoB) {
            $browser->loginAs($portfolio->user)
                    ->visit(route('portfolio.show'))
                    ->assertSee($cryptoA->name)
                    ->assertSee($cryptoA->symbol)
                    ->assertSee($cryptoA->pivot->amount)
                    ->assertSee($cryptoB->name)
                    ->assertSee($cryptoB->symbol)
                    ->assertSee($cryptoB->pivot->amount);
        });

        */

        $this->browse(function (Browser $browser) use($portfolio, $cryptoA, $cryptoB) {
            $browser->loginAs($portfolio->user)
                    ->visit(route('portfolio.show'))
                    ->pause(2000)
                    ->with('@crypto-list', function ($list) use($cryptoA, $cryptoB) {
                        $list->assertSee("Crypto")
                            ->assertSee("Amount")
                            ->assertSee("Price")
                            ->assertSee("Worth")
                            ->assertSee($cryptoA->name)
                            ->assertSee($cryptoA->symbol)
                            ->assertSee($cryptoA->pivot->amount)
                            ->assertSee($cryptoB->name)
                            ->assertSee($cryptoB->symbol)
                            ->assertSee($cryptoB->pivot->amount);
                    });
        });

    }

    // Helper Methods
    private function createPortfolio()
    {
        $user = User::factory()->create();
        $portfolio = Portfolio::factory()->create([
            'user_id' => $user->id
        ]);

        return $portfolio;
    }

    public function attachToPortfolio($portfolio, $cryptoId)
    {
        $portfolio->cryptos()->attach($cryptoId, ['amount' => rand(1, 1000)]);
    }


}
