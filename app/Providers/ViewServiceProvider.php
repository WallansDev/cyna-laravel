<?php

namespace App\Providers;

use App\Models\Ticket;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        view()->composer('layouts.base', function ($view) {
            $hasNewTickets = Ticket::where('status', 3)->exists();
            $view->with('hasNewTickets', $hasNewTickets);
        });
    }
}
