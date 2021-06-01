<?php

namespace Tests\Feature\Portfolio;

use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PortfolioEventsTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_registered_event_is_dispatched_when_a_user_registers()
    {
        Event::fake();

        $response = $this->post('/register', [
            'name' => 'New User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        Event::assertDispatched(Registered::class);
    }
}
