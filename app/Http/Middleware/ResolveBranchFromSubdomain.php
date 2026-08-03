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
        $host = $request->getHost(); // e.g. ipear.clarelab.com or localhost
        $subdomain = explode('.', $host)[0]; // e.g. ipear, fixd, phonelab

        // Try resolving branch by subdomain or slug
        $branch = Branch::where('subdomain', $host)
            ->orWhere('slug', $subdomain)
            ->first();

        if (!$branch) {
            // Fallback to first branch or default main store
            $branch = Branch::first();
        }

        if ($branch) {
            session([
                'active_branch_id' => $branch->id,
                'active_branch' => $branch,
            ]);
        }

        return $next($request);
    }
}
