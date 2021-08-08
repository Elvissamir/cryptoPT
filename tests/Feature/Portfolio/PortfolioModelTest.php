<?php

namespace Tests\Feature\Portfolio;

use Tests\TestCase;

use App\Models\User;
use App\Models\Crypto;
use App\Models\Portfolio;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;

class PortfolioModelTest extends TestCase
{
    //use RefreshDatabase;
    use RefreshDatabase;

    public function test_a_portfolio_is_assigned_to_a_registered_user()
    {
        $this->withoutExceptionHandling();

        $usersCount = User::all()->count();
        $portfoliosCount = Portfolio::all()->count();

        $response = $this->post('/register', [
            'name' => 'New User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertDatabaseCount('users', $usersCount + 1);
        $this->assertDatabaseCount('portfolios', $portfoliosCount + 1);

        $newUser = User::all()->last();
        $portfolio = Portfolio::all()->last();

        $this->assertEquals($newUser->id, $portfolio->user_id);
    }

    public function test_a_portolio_belongs_to_a_user()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $portfolio = Portfolio::factory()->create([
            'user_id' => $user->id,
        ]);
        
        $this->assertEquals($user->id, $portfolio->user->id);
    }

    public function test_a_portfolio_has_many_cryptos()
    {
        $this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();

        $cryptoA = Crypto::factory()->create();
        $cryptoB = Crypto::factory()->create();

        $this->attachToPortfolio($portfolio, $cryptoA->id);
        $this->attachToPortfolio($portfolio, $cryptoB->id);
        
        $this->assertEquals($cryptoA->id, $portfolio->cryptos[0]->id);
        $this->assertEquals($cryptoB->id, $portfolio->cryptos[1]->id);
        $this->assertInstanceOf(Crypto::class, $portfolio->cryptos[0]);
    }

    public function test_has_crypto_method()
    {
        $this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();

        $cryptoA = Crypto::factory()->create();
        $cryptoB = Crypto::factory()->create();

        $this->attachToPortfolio($portfolio, $cryptoA->id);

        $this->assertTrue($portfolio->hasCrypto($cryptoA->cg_id));
        $this->assertFalse($portfolio->hasCrypto($cryptoB->cg_id));
    }

    public function test_find_crypto_method_finds_the_crypto_by_name()
    {
        $this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();

        $nameA = 'CryptoA';
        $nameB = 'CryptoB';

        $cryptoA = Crypto::factory()->create(['cg_id' => $nameA]);
        $cryptoB = Crypto::factory()->create(['cg_id' => $nameB]);

        $this->attachToPortfolio($portfolio, $cryptoA->id);
        $this->attachToPortfolio($portfolio, $cryptoB->id);

        $this->assertEquals($cryptoA->id, $portfolio->findCrypto($nameA)->id);
        $this->assertEquals($cryptoB->id, $portfolio->findCrypto($nameB)->id);
    }

    public function test_find_crypto_method_returns_null_if_crypto_is_not_found()
    {
        $this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();

        $cg_idB = 'CryptoB';

        $cryptoA = Crypto::factory()->create();
        $cryptoB = Crypto::factory()->create(['cg_id' => $cg_idB]);

        $this->attachToPortfolio($portfolio, $cryptoA->id);

        $this->assertEquals(NULL, $portfolio->findCrypto($cg_idB));
    }


    public function test_addCrypto_method()
    {
        $this->withoutExceptionHandling();
    
        $portfolio = $this->createPortfolio();
        
        $crypto = Crypto::factory()->create();
        $amount = rand(1, 1000);
    
        $portfolio->addCrypto($crypto->id, $amount);
    
        $this->assertTrue($portfolio->hasCrypto($crypto->cg_id));
    }

    public function test_removeCrypto()
    {
        $this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        
        $crypto = Crypto::factory()->create();
        $amount = rand(1, 1000);
    
        $portfolio->addCrypto($crypto->id, $amount);

        $portfolio->removeCrypto($crypto->id);

        $this->assertFalse($portfolio->hasCrypto($crypto->cg_id));
    }
    
    public function test_updateCryptoAmount()
    {
        $this->withoutExceptionHandling();

        $portfolio = $this->createPortfolio();
        
        $crypto = Crypto::factory()->create();

        $amountA = rand(1, 1000);
        $portfolio->addCrypto($crypto->id, $amountA);

        $amountB = rand(1,1000);
        $portfolio->updateCryptoAmount($crypto->id, $amountB);
        
        $this->assertEquals($amountB, $portfolio->findCrypto($crypto->cg_id)->pivot->amount);
    }
}
