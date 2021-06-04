<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Portfolio;

use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_has_a_portfolio()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $portfolio = Portfolio::factory()->create([
            'user_id' => $user->id,
        ]);
        
        $this->assertEquals($portfolio->id, $user->portfolio->id);
    }
}
