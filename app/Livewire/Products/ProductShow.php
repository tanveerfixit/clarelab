<?php

namespace App\Livewire\Products;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use App\Models\Product;

#[Layout('components.layouts.app', ['title' => 'Product Information - Phone Lab POS'])]
class ProductShow extends Component
{
    public Product $product;
    public string $activeTab = 'info'; // 'info', 'pricing', 'activity'
    public bool $showManageDropdown = false;

    // Add Inventory form fields
    public bool $showAddInventory = false;
    public ?int $supplier_id = null;
    public string $cost = '0.00';
    public int $qty = 0;
    public string $serial_numbers = '';
    public bool $print_barcode = false;

    // New Supplier form fields
    public bool $showNewSupplierForm = false;
    public string $new_supplier_name = '';
    public string $new_supplier_email = '';
    public string $new_supplier_phone = '';

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->cost = number_format($this->product->cost_price, 2, '.', '');
    }

    #[Computed]
    public function suppliers()
    {
        return \App\Models\Supplier::orderBy('name')->get();
    }

    public function toggleAddInventory()
    {
        $this->showAddInventory = !$this->showAddInventory;
        if ($this->showAddInventory) {
            $this->cost = number_format($this->product->cost_price, 2, '.', '');
            $this->qty = 0;
            $this->serial_numbers = '';
            $this->supplier_id = $this->product->supplier_id;
        }
    }

    public function updatedSerialNumbers()
    {
        // For serialized products, quantity is derived from the number of serial numbers entered
        if ($this->product->has_serial || $this->product->type === 'serialized') {
            $lines = array_filter(array_map('trim', explode("\n", $this->serial_numbers)));
            $this->qty = count($lines);
        }
    }

    public function toggleNewSupplier()
    {
        $this->showNewSupplierForm = !$this->showNewSupplierForm;
        if (!$this->showNewSupplierForm) {
            $this->new_supplier_name = '';
            $this->new_supplier_email = '';
            $this->new_supplier_phone = '';
        }
    }

    public function saveNewSupplier()
    {
        $this->validate([
            'new_supplier_name' => 'required|string|max:255',
            'new_supplier_email' => 'nullable|email|max:255',
            'new_supplier_phone' => 'nullable|string|max:255',
        ]);

        $supplier = \App\Models\Supplier::create([
            'name' => $this->new_supplier_name,
            'email' => $this->new_supplier_email,
            'phone' => $this->new_supplier_phone,
        ]);

        $this->supplier_id = $supplier->id;
        $this->showNewSupplierForm = false;
        $this->new_supplier_name = '';
        $this->new_supplier_email = '';
        $this->new_supplier_phone = '';

        $this->dispatch('show-toast', message: "Supplier '{$supplier->name}' created successfully!");
    }

    public function addInventory(\App\Modules\Inventory\Services\InventoryService $inventoryService)
    {
        $rules = [
            'cost' => 'required|numeric|min:0',
        ];

        if ($this->product->has_serial || $this->product->type === 'serialized') {
            $rules['serial_numbers'] = 'required|string';
        } else {
            $rules['qty'] = 'required|integer|min:1';
        }

        $this->validate($rules);

        $branchId = session('active_branch_id', 1);
        $serialNumbers = [];

        if ($this->product->has_serial || $this->product->type === 'serialized') {
            $serialNumbers = array_filter(array_map('trim', explode("\n", $this->serial_numbers)));
            $this->qty = count($serialNumbers);

            if ($this->qty === 0) {
                $this->addError('serial_numbers', 'Please enter at least one serial number.');
                return;
            }
        }

        try {
            $inventoryService->incrementStock(
                branchId: $branchId,
                productId: $this->product->id,
                quantity: $this->qty,
                costPrice: (float)$this->cost,
                supplierId: $this->supplier_id,
                serialNumbers: $serialNumbers
            );

            $this->product = Product::find($this->product->id);
            $this->showAddInventory = false;
            $this->qty = 0;
            $this->serial_numbers = '';

            $this->dispatch('show-toast', message: "Inventory added successfully!");
        } catch (\Exception $e) {
            $this->addError('serial_numbers', $e->getMessage());
        }
    }

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    public function toggleManageDropdown()
    {
        $this->showManageDropdown = !$this->showManageDropdown;
    }

    public function archiveProduct()
    {
        $name = $this->product->name;
        $this->product->delete();
        $this->dispatch('show-toast', message: "Product '{$name}' archived successfully!");
        return redirect()->to('/products');
    }

    public function render()
    {
        return view('components.products.⚡product-show');
    }
}
