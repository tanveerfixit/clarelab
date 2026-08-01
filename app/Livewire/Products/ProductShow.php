<?php

namespace App\Livewire\Products;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Product;

#[Layout('components.layouts.app', ['title' => 'Product Information - Phone Lab POS'])]
class ProductShow extends Component
{
    public Product $product;
    public string $activeTab = 'info'; // 'info', 'pricing', 'activity'
    public bool $showManageDropdown = false;

    public function mount(Product $product)
    {
        $this->product = $product;
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
