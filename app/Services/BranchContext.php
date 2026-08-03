<?php

namespace App\Services;

use App\Models\Branch;

class BranchContext
{
    /**
     * Get active branch instance from session or database fallback.
     */
    public static function current(): ?Branch
    {
        if (auth()->check() && auth()->user()->branch_id) {
            $branch = auth()->user()->branch ?? Branch::find(auth()->user()->branch_id);
            if ($branch) {
                session(['active_branch' => $branch, 'active_branch_id' => $branch->id]);
                return $branch;
            }
        }

        if (session()->has('active_branch')) {
            return session('active_branch');
        }

        $branchId = session('active_branch_id', 1);
        $branch = Branch::find($branchId) ?? Branch::first();

        if ($branch) {
            session(['active_branch' => $branch, 'active_branch_id' => $branch->id]);
        }

        return $branch;
    }

    public static function name(): string
    {
        return static::current()?->name ?? 'Phone Lab';
    }

    public static function logo(): ?string
    {
        return static::current()?->logo_path;
    }

    public static function colorPrimary(): string
    {
        return static::current()?->color_primary ?? '#1e40af';
    }

    public static function invoicePrefix(): string
    {
        return static::current()?->invoice_prefix ?? 'PL';
    }

    public static function receiptHeader(): string
    {
        return static::current()?->receipt_header ?? (static::name() . ' - Mobile Repairs & Sales');
    }

    public static function receiptFooter(): string
    {
        return static::current()?->receipt_footer ?? 'Thank you for your business!';
    }

    public static function address(): string
    {
        return static::current()?->address ?? 'Main Branch';
    }

    public static function phone(): string
    {
        return static::current()?->phone ?? '';
    }
}
