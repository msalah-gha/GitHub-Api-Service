<?php

namespace App\Providers;

use App\Http\Controllers\ThirdParty\VersionControlSystems\Search\SearchRepositories\Providers\GitHub;
use App\Http\Controllers\ThirdParty\VersionControlSystems\Search\SearchRepositories\SearchRepositoryContract;
use Illuminate\Support\ServiceProvider;

class VcsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(SearchRepositoryContract::class, function ($app) {
            return new GitHub;
        });
    }
}
