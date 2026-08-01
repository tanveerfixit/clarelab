<?php

namespace App\Livewire\Products;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app', ['title' => 'Manage Products - Phone Lab POS'])]
class ProductIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $selectedType = 'all'; 
    public string $selectedManufacturer = 'all';
    public string $selectedCategory = 'all';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    public bool $showAddModal = false;

    // Form fields for Quick Add
    public string $name = '';
    public string $sku = '';
    public string $barcode = '';
    public string $manufacturer = '';
    public string $category_name = '';
    public string $type = 'standard';
    public ?float $cost_price = 0.00;
    public ?float $selling_price = 0.00;
    public int $stock_quantity = 0;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedType' => ['except' => 'all'],
        'selectedManufacturer' => ['except' => 'all'],
        'selectedCategory' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedType()
    {
        $this->resetPage();
    }

    public function updatingSelectedManufacturer()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function sortBy(string $field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openAddModal()
    {
        $this->reset(['name', 'sku', 'barcode', 'manufacturer', 'category_name', 'type', 'cost_price', 'selling_price', 'stock_quantity']);
        $this->showAddModal = true;
    }

    public function closeAddModal()
    {
        $this->showAddModal = false;
    }

    public function saveProduct()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'barcode' => 'nullable|string|max:100',
            'type' => 'required|in:standard,serialized,variable,service',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'nullable|integer',
        ]);

        Product::create([
            'name' => $this->name,
            'manufacturer' => $this->manufacturer ?: null,
            'category_name' => $this->category_name ?: null,
            'sku' => $this->sku ?: ('SKU-' . strtoupper(substr(md5(uniqid()), 0, 6))),
            'barcode' => $this->barcode,
            'type' => $this->type,
            'cost_price' => $this->cost_price ?: 0.00,
            'selling_price' => $this->selling_price ?: 0.00,
            'stock_quantity' => $this->type === 'service' ? 0 : ($this->stock_quantity ?: 0),
            'manage_stock' => $this->type !== 'service',
            'is_active' => true,
        ]);

        $this->dispatch('show-toast', message: "Product '{$this->name}' created successfully!");
        $this->showAddModal = false;
        $this->reset(['name', 'sku', 'barcode', 'manufacturer', 'category_name', 'type', 'cost_price', 'selling_price', 'stock_quantity']);
    }

    public function render()
    {
        $query = Product::query();

        if (trim($this->search)) {
            $s = trim($this->search);
            $query->where(function($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('sku', 'like', "%{$s}%")
                  ->orWhere('barcode', 'like', "%{$s}%");
            });
        }

        if ($this->selectedType !== 'all') {
            $query->where('type', $this->selectedType);
        }

        if ($this->selectedManufacturer !== 'all') {
            if ($this->selectedManufacturer === 'none') {
                $query->whereNull('manufacturer');
            } else {
                $query->where('manufacturer', $this->selectedManufacturer);
            }
        }

        if ($this->selectedCategory !== 'all') {
            if ($this->selectedCategory === 'none') {
                $query->whereNull('category_name');
            } else {
                $query->where('category_name', $this->selectedCategory);
            }
        }

        $products = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate(25);

        // Fetch distinct manufacturers and categories for filter dropdowns
        $manufacturers = Product::whereNotNull('manufacturer')
            ->where('manufacturer', '!=', '')
            ->distinct()
            ->pluck('manufacturer')
            ->sort()
            ->values();

        $categories = Product::whereNotNull('category_name')
            ->where('category_name', '!=', '')
            ->distinct()
            ->pluck('category_name')
            ->sort()
            ->values();

        return view('components.products.⚡product-index', [
            'products' => $products,
            'manufacturers' => $manufacturers,
            'categories' => $categories,
        ]);
    }
}
