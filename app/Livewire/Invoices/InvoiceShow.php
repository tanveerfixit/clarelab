<?php

namespace App\Livewire\Invoices;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Invoice;

#[Layout('components.layouts.app', ['title' => 'View Invoice'])]
class InvoiceShow extends Component
{
    public Invoice $invoice;

    public function mount(string $invoice_number)
    {
        $this->invoice = Invoice::where('invoice_number', $invoice_number)
            ->with(['customer', 'user', 'items'])
            ->firstOrFail();
    }

    public function render()
    {
        return view('components.invoices.⚡invoice-show');
    }
}
