<?php

namespace App\Providers;

use App\Repositories\Profile\ProfileMessage\ProfileMessageRepository;
use App\Repositories\Profile\ProfileMessage\ProfileMessageRepositoryInterface;
use App\Repositories\Profile\ProfileRepository;
use App\Repositories\Profile\ProfileRepositoryInterface;
use App\Repositories\UserProfileAction\UserProfileActionInterface;
use App\Repositories\UserProfileAction\UserProfileActionRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            ProfileRepositoryInterface::class,
            ProfileRepository::class
        );

        $this->app->bind(
            ProfileMessageRepositoryInterface::class,
            ProfileMessageRepository::class,
        );

        $this->app->bind(
            UserProfileActionInterface::class,
            UserProfileActionRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
