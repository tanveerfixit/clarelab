<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Branch;

class InvoiceNumberGenerator
{
    /**
     * Generate the next branch-scoped invoice number.
     * Example: IPEAR-2026-0001, FIXD-2026-0005
     */
    public static function generate(?Branch $branch = null): string
    {
        return DB::transaction(function () use ($branch) {
            $targetBranch = $branch ?? BranchContext::current();

            if (!$targetBranch) {
                $prefix = 'ph';
                $nextNum = rand(1, 999);
            } else {
                // Lock branch row for safe update
                $b = Branch::where('id', $targetBranch->id)->lockForUpdate()->first();
                
                // Get branch name or fallback
                $branchName = $b->name ?: 'Phone Lab';
                
                // Remove spaces and get first two letters, lowercased
                $cleanName = str_replace(' ', '', $branchName);
                $prefix = strtolower(substr($cleanName, 0, 2));
                
                $nextNum = $b->invoice_next_number ?: 1;

                // Increment counter
                $b->increment('invoice_next_number');
            }

            $paddedSeq = str_pad((string)$nextNum, 3, '0', STR_PAD_LEFT);

            return "{$prefix}{$paddedSeq}";
        });
    }
}
