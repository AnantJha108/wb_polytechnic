<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CheckMenuAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $slug = request()->segment(3)
            ?? request()->segment(2)
            ?? request()->segment(1);

        $menu = Menu::where('slug', $slug)->first();

        if ($menu) {
            $menuIds = DB::table('menu_user_maps')
                ->where('user_id', Auth::id())
                ->pluck('menu_id')
                ->toArray();

            if (!in_array($menu->id, $menuIds)) {
                abort(403);
            }
        }
        return $next($request);
    }
}
