<?php

namespace App\Http\Middleware;

use Closure;
Use Auth;
Use Redirect;
Use Session;
Use App;
Use Config;

class GuestFront 
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
	 //    if(!empty(Auth::guard('admin')->user())){
  //           if(Auth::guard('admin')->user()->user_role  == SUPER_ADMIN_ROLE_ID){
  //               return Redirect::to('/adminpnlx');
  //           }
		// }
		return $next($request);
    }
}
