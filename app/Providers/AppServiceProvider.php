<?php

namespace App\Providers;

use App\Models\Listing;
use App\Policies\ListingPolicy;
use App\Services\KhaltiService;
use App\Services\ListingService;
use App\Services\UnlockService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(KhaltiService::class);
        $this->app->singleton(ListingService::class);
        $this->app->singleton(UnlockService::class, function ($app) {
            return new UnlockService($app->make(KhaltiService::class));
        });
    }

    public function boot(): void
    {
        Gate::policy(Listing::class, ListingPolicy::class);
        Gate::define('admin', fn($user) => $user->isAdmin());

        // Use custom dark pagination views
        \Illuminate\Pagination\Paginator::defaultView('partials.pagination');
    }
}
