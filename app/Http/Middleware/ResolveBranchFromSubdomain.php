<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Branch;

class ResolveBranchFromSubdomain
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->branch_id) {
                session([
                    'active_branch_id' => $user->branch_id,
                    'active_branch' => $user->branch ?? Branch::find($user->branch_id),
                ]);
            }
        } else {
            // Fallback for guests (e.g. on login/landing page)
            $branch = Branch::first();
            if ($branch) {
                session([
                    'active_branch_id' => $branch->id,
                    'active_branch' => $branch,
                ]);
            }
        }

        return $next($request);
    }
}
