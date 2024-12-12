<?php
declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use App\Policies\EmployeePolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('fetchEmployee', function (User $loggedInUser, User $requestedUser) {
            return $loggedInUser->role->weight >= $requestedUser->role->weight;
        });

        Gate::define('deleteEmployee',function(User $loggedInUser, User $requestedUser) {
            return $loggedInUser->role->weight >= $requestedUser->role->weight;
        });

        Gate::define('updateEmployee',function(User $loggedInUser, User $requestedUser) {
            return $loggedInUser->role->weight >= $requestedUser->role->weight;
        });

        Gate::define('bulkDeleteEmployee', function(User $loggedInUser, array $idsArray) {
            $users = User::whereIn('id', $idsArray)->get();

            foreach ($users as $user) {
                if ($loggedInUser->role->weight >= $user->role->weight) {
                    return true;
                }
            }

            return false;
        });
    }
}
