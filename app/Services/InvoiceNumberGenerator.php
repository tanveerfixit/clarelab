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
                $prefix = 'INV';
                $nextNum = rand(1000, 9999);
            } else {
                // Lock branch row for safe update
                $b = Branch::where('id', $targetBranch->id)->lockForUpdate()->first();
                $prefix = strtoupper($b->invoice_prefix ?: 'INV');
                $nextNum = $b->invoice_next_number ?: 1;

                // Increment counter
                $b->increment('invoice_next_number');
            }

            $year = date('Y');
            $paddedSeq = str_pad((string)$nextNum, 4, '0', STR_PAD_LEFT);

            return "{$prefix}-{$year}-{$paddedSeq}";
        });
    }
}
