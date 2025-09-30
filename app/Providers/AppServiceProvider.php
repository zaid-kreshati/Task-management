<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\services\PassportService;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\User;
use App\Models\Task;
use Illuminate\Pagination\Paginator;


use App\Observers\ModelActivityObserver;




class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // Bind PassportService into the container
        $this->app->singleton(PassportService::class, function ($app) {
            return new PassportService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {




        Relation::enforceMorphMap([
            'Task' => 'App\Models\Task',
            'Subtask' => 'App\Models\Subtask',
            'user' => 'App\Models\User',

        ]);
    }
}
