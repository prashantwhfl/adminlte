<?php

namespace App\Http\Middleware;

use Closure;
Use Auth,Session,Request;
Use Redirect;

class AuthAdmin 
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
		if(!empty(Auth::guard('admin')->guest())){
			return Redirect::to('/adminpnlx');
        }
		// if(Auth::guard('admin')->user()->user_role  != SUPER_ADMIN_ROLE_ID){
		// 	return Redirect::to('/login');
		// }
        return $next($request);
    }
}
