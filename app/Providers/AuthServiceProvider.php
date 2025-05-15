<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use App\Policies\UserPolicy;
use App\Policies\TaskPolicy;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider  as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Task::class => TaskPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view-logs', function (User $user) {
            return $user->role === 'admin';
        });
    }
}
