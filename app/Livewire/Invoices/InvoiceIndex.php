<?php

namespace App\Livewire\Invoices;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Invoice;

#[Layout('components.layouts.app', ['title' => 'Invoices'])]
class InvoiceIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'latest'; // 'latest', 'oldest', 'highest', 'lowest'

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Invoice::query()
            ->with(['customer', 'user', 'items']);

        // Filter/Search
        $search = trim($this->search);
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($q3) use ($search) {
                      $q3->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('items', function ($q4) use ($search) {
                      $q4->where('product_name', 'like', "%{$search}%");
                  });
            });
        }

        // Sorting
        if ($this->sortBy === 'latest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($this->sortBy === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($this->sortBy === 'highest') {
            $query->orderBy('grand_total', 'desc');
        } elseif ($this->sortBy === 'lowest') {
            $query->orderBy('grand_total', 'asc');
        }

        $invoices = $query->paginate(15);

        return view('components.invoices.⚡invoice-index', [
            'invoices' => $invoices
        ]);
    }
}
