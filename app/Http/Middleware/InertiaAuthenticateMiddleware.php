<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate;
use Inertia\Inertia;

class InertiaAuthenticateMiddleware extends Authenticate
{
    protected function unauthenticated($request, array $guards)
    {
        Inertia::clearHistory();

        throw new AuthenticationException(
            'Unauthenticated.',
            $guards,
            $request->expectsJson() ? null : $this->redirectTo($request),
        );
    }
}
