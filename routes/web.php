<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\HomeDashboard;
use App\Livewire\Pos\CashRegister;
use App\Livewire\Repairs\RepairBooking;
use App\Livewire\Products\ProductIndex;
use App\Livewire\Products\ProductShow;
use App\Livewire\Products\ProductFormView;

use App\Livewire\Auth\Login;
use App\Livewire\Settings\GettingStarted;

use Illuminate\Support\Facades\Auth;

use App\Livewire\Repairs\RepairShow;
use App\Livewire\Customers\CustomerIndex;
use App\Livewire\Customers\CustomerShow;
use App\Livewire\Invoices\InvoiceIndex;
use App\Livewire\Invoices\InvoiceShow;

Route::middleware('web')->group(function () {
    Route::get('/', Login::class)->name('login');

    // Authenticated Routes
    Route::middleware('auth')->group(function () {
        Route::any('/logout', function () {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');
        })->name('logout');
        Route::get('/register', CashRegister::class);
        Route::get('/repairs', RepairBooking::class);
        Route::get('/repairs/create', RepairBooking::class);
        Route::get('/repairs/{ticket}', RepairShow::class)->name('repairs.show');
        Route::get('/products', ProductIndex::class);
        Route::get('/products/create', ProductFormView::class);
        Route::get('/products/{product}', ProductShow::class);
        Route::get('/products/{product}/edit', ProductFormView::class);
        Route::get('/customers', CustomerIndex::class);
        Route::get('/customers/{customer}', CustomerShow::class);
        Route::get('/invoices', InvoiceIndex::class);
        Route::get('/invoices/{invoice_number}', InvoiceShow::class)->name('invoices.show');
        Route::get('/getting-started', GettingStarted::class);
    });
});
