<?php

namespace App\Providers;

use App\Models\SupportTicket;
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
            $hasNewTickets = SupportTicket::where('status', 3)->exists();
            $view->with('hasNewTickets', $hasNewTickets);
        });
    }
}
