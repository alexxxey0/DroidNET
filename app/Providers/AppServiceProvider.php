<?php

namespace App\Providers;

use App\Models\Friendship;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
    public function boot(): void {

        view()->composer('*', function($view) {
            if (Auth::check()) {
                $requests_received = Friendship::select('request_sender')->where('request_receiver', '=', auth()->user()->username)
                ->where('status', '=', 'PENDING')->pluck('request_sender')->toArray();
                
                $received_requests_count = count($requests_received);
                $view->with('received_requests_count', $received_requests_count);
            } else {
                $view->with('received_requests_count', 0);
            }
        });
    }
}
