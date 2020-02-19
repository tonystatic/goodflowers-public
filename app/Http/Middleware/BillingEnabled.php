<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;

class BillingEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! (bool) config('billing.enabled')) {
            return standard_response()->error(
                'Система оплаты в данный момент недоступна. Извините за причинённые неудобства.'
            );
        }

        return $next($request);
    }
}
