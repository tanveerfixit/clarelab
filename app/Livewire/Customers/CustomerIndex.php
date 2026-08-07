<?php

namespace App\Livewire\Customers;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Customer;
use Illuminate\Support\Str;

#[Layout('components.layouts.app', ['title' => 'Manage Customers'])]
class CustomerIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $selectedType = 'all'; // 'all', 'completed', 'booked', 'processing'
    public string $sortBy = 'name'; // 'name', 'company', 'email'

    // Create Modal state
    public bool $showCreateModal = false;
    public string $name = '';
    public string $company = '';
    public string $email = '';
    public string $phone = '';
    public string $landline = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'email' => 'nullable|email|max:255',
        'company' => 'nullable|string|max:255',
        'landline' => 'nullable|string|max:20',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function resetForm()
    {
        $this->name = '';
        $this->company = '';
        $this->email = '';
        $this->phone = '';
        $this->landline = '';
    }

    public function createCustomer()
    {
        $this->validate();

        $baseSlug = Str::slug($this->name);
        $slug = $baseSlug ?: 'customer';
        
        // Ensure uniqueness of slug
        $count = Customer::where('slug', 'like', $slug . '%')->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $customer = Customer::create([
            'business_id' => 1, // Default business
            'name' => $this->name,
            'slug' => $slug,
            'company' => $this->company,
            'email' => $this->email,
            'phone' => $this->phone,
            'landline' => $this->landline,
        ]);

        $this->showCreateModal = false;
        $this->dispatch('show-toast', message: 'Customer created successfully!');
        
        return redirect()->to('/customers/' . $customer->slug);
    }

    public function render()
    {
        $query = Customer::query();

        // 1. Search Filter
        if ($this->search !== '') {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('company', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // 2. Status Filter
        if ($this->selectedType === 'booked') {
            $query->whereHas('repairs', function ($q) {
                $q->where('status', 'Received');
            })->whereDoesntHave('repairs', function ($q) {
                $q->where('status', 'In Progress');
            });
        } elseif ($this->selectedType === 'processing') {
            $query->whereHas('repairs', function ($q) {
                $q->where('status', 'In Progress');
            });
        } elseif ($this->selectedType === 'completed') {
            $query->where(function ($q) {
                $q->whereDoesntHave('repairs')
                  ->orWhere(function ($sub) {
                      $sub->whereDoesntHave('repairs', function ($q) {
                          $q->whereIn('status', ['In Progress', 'Received']);
                      });
                  });
            });
        }

        // 3. Sorting
        if ($this->sortBy === 'company') {
            $query->orderByRaw('CASE WHEN company IS NULL THEN 1 ELSE 0 END, company ASC');
        } elseif ($this->sortBy === 'email') {
            $query->orderByRaw('CASE WHEN email IS NULL THEN 1 ELSE 0 END, email ASC');
        } else {
            $query->orderBy('name', 'asc');
        }

        $customers = $query->paginate(10);

        return view('components.customers.⚡customer-index', [
            'customers' => $customers,
        ]);
    }
}
