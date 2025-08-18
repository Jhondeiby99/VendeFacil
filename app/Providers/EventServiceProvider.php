<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use App\Listeners\SyncCartAfterLogin;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            SyncCartAfterLogin::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
