<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;

class LogAuthenticationActivity
{
    public function __construct(protected Request $request)
    {
    }

    public function handle(object $event): void
    {
        $user = $event->user;
        $eventType = ($event instanceof Login) ? 'login' : 'logout';
        $description = ($event instanceof Login) ? "User logged in" : "User logged out";

        ActivityLog::create([
            'user_id' => $user->id,
            'event' => $eventType,
            'description' => $description,
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'created_at' => now(),
        ]);
    }
}
