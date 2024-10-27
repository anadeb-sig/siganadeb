<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\UserActivity;
use Illuminate\Support\Str;

class LogUserActivity
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check()) {
            $userId = Auth::id();
            $activityType = $request->method();
            $description = $request->fullUrl();
            
            // Vérifier si le lien ne se termine pas par "fetch" et autres
            if (!Str::endsWith($description, 'fetch')) { 
                if (!Str::endsWith($description, '/repas/par_fin_date')) {
                    if (!Str::endsWith($description, '/repas/par_sexe')) {
                        if (!Str::endsWith($description, '/repas/par_fin')) {
                            if (!Str::endsWith($description, '/repas/char_parregion')) {
                                UserActivity::create([
                                    'user_id' => $userId,
                                    'activity_type' => $activityType,
                                    'description' => $description,
                                ]);
                            }
                        }
                    }
                }
            }
        }

        return $response;
    }
}