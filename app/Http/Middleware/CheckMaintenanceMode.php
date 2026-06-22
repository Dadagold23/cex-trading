<?php

namespace App\Http\Middleware;

use App\Services\SettingService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response 
    {
        $enabled = false;
        
        if (Schema::hasTable('system_settings')) {
            $setting = 
            DB::table('system_settings')->where('key', 'maintenance_mode')
            ->value('value') ?? false;
        }

        if ($enabled && !auth()->check()) {
            abort(503, 'The platform is currently under maintenance.');
        }

        return $next($request);
    }
}
