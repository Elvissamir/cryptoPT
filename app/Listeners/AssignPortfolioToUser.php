<?php

namespace App\Listeners;

use App\Models\Portfolio;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AssignPortfolioToUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $portfolio = Portfolio::create([
            'user_id' => $event->user->id
        ]);
    }
}
