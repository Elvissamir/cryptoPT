<?php

namespace Tests\Feature\Portfolio;

use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Listeners\AssignPortfolioToUser;
use Illuminate\Foundation\Testing\WithFaker;

class PortfolioListenersTest extends TestCase
{
    public function test_AssignPortfolioToUser_listener_is_attached_to_registered_event()
    {
        Event::fake();

        Event::assertListening(Registered::class, AssignPortfolioToUser::class);
    }
    
}
