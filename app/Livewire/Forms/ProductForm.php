<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;
use App\Models\Product;

class ProductForm extends Form
{
    public ?Product $product = null;

    #[Validate('required|string|max:255')]
    public string $product_name = '';

    public ?string $manufacturer_id = '';
    public ?string $category_id = '';

    #[Validate('required|numeric|min:0')]
    public ?float $selling_price = 0.00;

    public ?string $sku = '';

    public string $inventory_tracking = 'track'; // 'track', 'labor', 'bundles'
    public bool $has_serial = false;

    public bool $is_taxable = true;
    public ?int $min_stock_level = 5;

    public ?float $min_sales_price = null;
    public ?string $color = '';
    public ?string $condition = '';
    public ?string $storage = '';
    public ?string $description = '';
    public ?string $alert_message = '';

    public function setProduct(Product $product)
    {
        $this->product = $product;
        $this->product_name = $product->name;
        $this->manufacturer_id = $product->manufacturer ?? '';
        $this->category_id = $product->category_name ?? '';
        $this->selling_price = floatval($product->selling_price);
        $this->sku = $product->sku ?? '';
        $this->inventory_tracking = $product->inventory_tracking ?? ($product->type === 'service' ? 'labor' : 'track');
        $this->has_serial = (bool)$product->has_serial || $product->type === 'serialized';
        $this->is_taxable = (bool)$product->is_taxable;
        $this->min_stock_level = $product->min_stock_level ?? 5;
        $this->min_sales_price = $product->min_sales_price ? floatval($product->min_sales_price) : null;
        $this->color = $product->color ?? '';
        $this->condition = $product->condition ?? '';
        $this->storage = $product->storage ?? '';
        $this->description = $product->description ?? '';
        $this->alert_message = $product->alert_message ?? '';
    }

    public function store(): Product
    {
        $this->validate();

        $type = in_array($this->inventory_tracking, ['standard', 'serialized', 'variable', 'service']) 
            ? $this->inventory_tracking 
            : 'standard';

        $hasSerial = $this->has_serial || $type === 'serialized';
        $manageStock = in_array($type, ['standard', 'serialized', 'variable']);

        return Product::create([
            'name' => $this->product_name,
            'manufacturer' => $this->manufacturer_id ?: null,
            'category_name' => $this->category_id ?: null,
            'selling_price' => $this->selling_price ?: 0.00,
            'sku' => $this->sku ?: ('SKU-' . strtoupper(substr(md5(uniqid()), 0, 6))),
            'type' => $type,
            'inventory_tracking' => $this->inventory_tracking,
            'has_serial' => $hasSerial,
            'is_taxable' => $this->is_taxable,
            'min_stock_level' => $this->min_stock_level ?: 5,
            'min_sales_price' => $this->min_sales_price,
            'color' => $this->color ?: null,
            'condition' => $this->condition ?: null,
            'storage' => $this->storage ?: null,
            'description' => $this->description ?: null,
            'alert_message' => $this->alert_message ?: null,
            'manage_stock' => $manageStock,
            'is_active' => true,
        ]);
    }

    public function update(): Product
    {
        $this->validate();

        $type = in_array($this->inventory_tracking, ['standard', 'serialized', 'variable', 'service']) 
            ? $this->inventory_tracking 
            : 'standard';

        $hasSerial = $this->has_serial || $type === 'serialized';
        $manageStock = in_array($type, ['standard', 'serialized', 'variable']);

        $this->product->update([
            'name' => $this->product_name,
            'manufacturer' => $this->manufacturer_id ?: null,
            'category_name' => $this->category_id ?: null,
            'selling_price' => $this->selling_price ?: 0.00,
            'sku' => $this->sku ?: $this->product->sku,
            'type' => $type,
            'inventory_tracking' => $this->inventory_tracking,
            'has_serial' => $hasSerial,
            'is_taxable' => $this->is_taxable,
            'min_stock_level' => $this->min_stock_level ?: 5,
            'min_sales_price' => $this->min_sales_price,
            'color' => $this->color ?: null,
            'condition' => $this->condition ?: null,
            'storage' => $this->storage ?: null,
            'description' => $this->description ?: null,
            'alert_message' => $this->alert_message ?: null,
            'manage_stock' => $manageStock,
        ]);

        return $this->product;
    }
}
