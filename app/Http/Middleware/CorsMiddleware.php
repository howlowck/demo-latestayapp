<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */

	public function handle(Request $request, Closure $next)
	{
		$response = $next($request);
		$response->headers->set('Access-Control-Allow-Origin', '*');
		$response->headers->set('Access-Control-Allow-Headers', 'X-Requested-With, Origin, X-Csrftoken, Content-Type, Accept, Authorization, Want-Cookies');
		$response->headers->set('Access-Control-Allow-Methods', 'OPTIONS,GET,POST,PUT,DELETE');
		return $response;
	}
}