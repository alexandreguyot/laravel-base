<?php

namespace App\Http\Middleware;

use App\Services\FeatureService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeatureFlag
{
    public function __construct(protected FeatureService $features) {}

    /**
     * Usage sur une route : ->middleware('feature:crm')
     * Plusieurs flags : ->middleware('feature:crm,lms')
     */
    public function handle(Request $request, Closure $next, string ...$features): Response
    {
        foreach ($features as $key) {
            if ($this->features->isDisabled(trim($key))) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Feature désactivée.'], 403);
                }

                return redirect()->route('home')
                    ->with('status', __('global.feature_disabled'));
            }
        }

        return $next($request);
    }
}
