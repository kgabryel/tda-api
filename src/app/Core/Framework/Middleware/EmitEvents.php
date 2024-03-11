<?php

namespace App\Core\Framework\Middleware;

use App\Core\Cqrs\AsyncEventBus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmitEvents
{
    private AsyncEventBus $asyncEventBus;

    public function __construct(AsyncEventBus $asyncEventBus)
    {
        $this->asyncEventBus = $asyncEventBus;
    }

    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        $this->asyncEventBus->run();
    }
}
