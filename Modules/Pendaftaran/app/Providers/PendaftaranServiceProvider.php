<?php

namespace Modules\Pendaftaran\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PendaftaranServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'pendaftaran');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        Route::middleware('web')->group(__DIR__.'/../../routes/web.php');
    }
}
