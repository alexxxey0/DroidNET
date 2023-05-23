<?php

namespace App\Providers;

use App\Models\Message;
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
                $received_requests_count = Friendship::select('request_sender')->where('request_receiver', '=', auth()->user()->username)
                ->where('status', '=', 'PENDING')->count();

                $role = auth()->user()->role;
                $unread_messages_count = Message::select('message_sender')->where('message_receiver', '=', auth()->user()->username)->where('status', '=', 'UNREAD')->groupBy('message_sender')->get()->toArray();
                $unread_messages_count = count($unread_messages_count);

                $view->with(['received_requests_count' => $received_requests_count, 'role' => $role, 'unread_messages_count' => $unread_messages_count]);
            } else {
                $view->with(['received_requests_count' => 0, 'role' => 'guest']);
            }
        });
    }
}
