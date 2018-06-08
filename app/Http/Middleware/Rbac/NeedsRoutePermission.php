<?php

namespace App\Http\Middleware\Rbac;

use App\Facades\Rbac;
use App\Permission;
use Closure;
use Illuminate\Support\Facades\Gate;

class NeedsRoutePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $permission = Rbac::getPermissionByRoute($request->route());

        if($permission === null) {
            abort(500);
        } elseif(!Rbac::hasPermission($permission)) {
            abort(403, "Je hebt geen toegang tot deze route.");
        }

        return $next($request);
    }
}
