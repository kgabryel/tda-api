<?php

namespace App\Core\Framework\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class StartTransaction
{
    public function handle(Request $request, Closure $next): Response
    {
        DB::beginTransaction();

        return $next($request);
    }
}
