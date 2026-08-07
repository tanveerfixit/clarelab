<?php

namespace App\Livewire\Customers;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Customer;

#[Layout('components.layouts.app', ['title' => 'Customer Details'])]
class CustomerShow extends Component
{
    public Customer $customer;

    public function mount(Customer $customer)
    {
        $this->customer = $customer->load(['repairs', 'invoices']);
    }

    public function render()
    {
        return view('components.customers.⚡customer-show');
    }
}
