<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Portfolio;
use Database\Seeders\CryptoSeeder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginAsDemoUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_is_created_when_the_guest_visits_as_demo_user() {

        $this->withoutExceptionHandling();
        $this->seed(CryptoSeeder::class);
        
        $this->assertDatabaseCount('users', 0);

        $response = $this->from(route('login'))->post(route('demoLogin.store'));

        $this->assertDatabaseCount('users', 1);
        
        $demoUser = User::all()->first();
        $portfolio = Portfolio::all()->first();

        $this->assertAuthenticated();
        $this->assertEquals($demoUser->name, 'Demo');
        $this->assertEquals($portfolio->cryptos()->count(), 5);
        $this->assertEquals($portfolio->cryptos()->first()->cg_id, 'bitcoin');

        $response->assertRedirect(route('portfolioCryptos.show'));
    }
}
