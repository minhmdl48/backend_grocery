<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $models = [
            'User',
            'Banner',
            'Product',
            'Category',
            'Order',
            'OrderItem',
            'Cart'
        ];

        foreach ($models as $model) {
            $this->app->bind(
                "App\\Repositories\\{$model}\\{$model}RepositoryInterface",
                "App\\Repositories\\{$model}\\{$model}Repository"
            );
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
