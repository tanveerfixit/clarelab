<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Middleware\ResolveBranchFromSubdomain;
use App\Livewire\HomeDashboard;
use App\Livewire\Pos\CashRegister;
use App\Livewire\Repairs\RepairBooking;
use App\Livewire\Products\ProductIndex;
use App\Livewire\Products\ProductShow;
use App\Livewire\Products\ProductFormView;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
*/

Route::middleware([
    'web',
    InitializeTenancyBySubdomain::class,
    PreventAccessFromCentralDomains::class,
    ResolveBranchFromSubdomain::class,
])->group(function () {
    Route::get('/', HomeDashboard::class);
    Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
    Route::any('/logout', function () {
        \Illuminate\Support\Facades\Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
    Route::get('/register', CashRegister::class);
    Route::get('/repairs', RepairBooking::class);
    Route::get('/products', ProductIndex::class);
    Route::get('/products/{product}', ProductShow::class);
    Route::get('/products/create', ProductFormView::class);
    Route::get('/products/{product}/edit', ProductFormView::class);
});
