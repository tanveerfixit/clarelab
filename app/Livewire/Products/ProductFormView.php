<?php

namespace App\Livewire\Products;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Livewire\Forms\ProductForm;
use App\Models\Product;

#[Layout('components.layouts.app', ['title' => 'Product Details - Phone Lab POS'])]
class ProductFormView extends Component
{
    public ProductForm $form;
    public ?int $productId = null;
    public bool $isEditMode = false;

    // Inline Modals for Adding New Options
    public bool $showNewManufacturerModal = false;
    public string $newManufacturerName = '';

    public bool $showNewCategoryModal = false;
    public string $newCategoryName = '';

    public bool $showNewColorModal = false;
    public string $newColorName = '';

    // Dynamic Lists
    public array $manufacturers = [];
    public array $categories = [];
    public array $colors = [];
    public array $conditions = ['A', 'B', 'C', 'Refurbished', 'Parts Only', 'New'];

    public function mount(?int $product = null)
    {
        $this->loadDropdownOptions();

        if ($product) {
            $this->productId = $product;
            $this->isEditMode = true;
            $model = Product::findOrFail($product);
            $this->form->setProduct($model);
        }
    }

    public function loadDropdownOptions()
    {
        // Populate Manufacturers from requirement list
        $this->manufacturers = [
            '1Plus', '8040', 'A01 Core', 'A01', 'A1 Core', 'a10s', 'A11', 'a20e', 'A405FN', 'Acer',
            'Alcatel', 'all models', 'Amazon', 'Aokus', 'Apple', 'Asus', 'Atouchbo', 'Balance amount',
            'Baseus', 'blackberry', 'Blackview', 'BOROFONE', 'Budi', 'C11', 'camera', 'CAT', 'charger',
            'ChargingPort Clean', 'Compaq', 'DELL.', 'Dell/HP', 'DELL', 'Doro', 'DTS HEADPHONES;XV2.0',
            'dvd', 'E cig', 'Fire', 'Galaxy', 'Geek Bar', 'Generic', 'google nexsus', 'google pixel XL',
            'Google', 'GortFoneRepair', 'Grade A', 'Hammer 5', 'hammer', 'head', 'Hoco', 'HP', 'HTC',
            'Huawei', 'IMO', 'ipad 2', 'ipad mini', 'ipad', 'iphone 5s', 'iphone 6', 'iphone 7', 'iphone x',
            'iphone', 'ipod 4', 'ipod', 'iShine', 'j2 core', 'j810f', 'JBL', 'jcb', 'kingston', 'laptop',
            'lcd', 'lenova', 'LG', 'Lyca Mobile', 'macbook', 'Mi', 'mobile repair.', 'Mobitel', 'Modio',
            'Motorola', 'N.G.P Tobacco', 'Nintendo', 'Nokia 105', 'Nokias', 'Nokia', 'Oale', 'one plus 3t',
            'one plus 5T', 'OnePlus', 'OPPO', 'Other', 'p20 lite', 'Poco', 'psamrt z', 'psmart 2019',
            'Q2 Plus', 'REALME', 'Redmi', 'Remax', 'Samsung Galaxy', 'Samsungs', 'Samsung', 'SanDisk',
            'selfi stick', 'sodo', 'Sony', 'Soony Angels', 't 590', 'Tank', 'TCL', 'Telzeal', 'toshiba',
            'Vodafone', 'watch', 'WB-389BT', 'WILEYFOX', 'Wireless Headphones', 'xbox one s', 'xiomi',
            'y62019', 'ZTE'
        ];

        // Populate Categories from requirement list
        $this->categories = [
            'Accessories', 'Alcatel Tablet', 'Amazon', 'Apple', 'Balance', 'Baseus', 'Blade', 'Budi',
            'Bundle deal', 'Cable', 'Camera', 'Cat', 'CCTV', 'ChargingPort Clean', 'Computer Accessories',
            'Cuba', 'Dell Monitor', 'Dell', 'Desktop Computer', 'Doro', 'dorro', 'Dumpling', 'E cig',
            'Earbuds', 'Galaxy Phones', 'General Labour', 'hammer', 'Hoco', 'Honor', 'HP', 'Huawei',
            'IMO', 'iPhone', 'IT Support', 'Laptop bag', 'Laptop Repair', 'Laptop Sale (Used)', 'LCD',
            'Lyca', 'Mi', 'Mobile Covers', 'Mobile Trade-In Stock (Used)', 'mobile', 'Modio Tablet',
            'Motorola', 'Mouse pad', 'Nano Temper Glass', 'New Laptop', 'New', 'Nintendo', 'Nokia',
            'Oneplus', 'Oppo', 'Parts', 'Phone Repair', 'Phones', 'Phone', 'POCO', 'REALME', 'Redmi',
            'Refurbished Computer.', 'Refurbished Mobile Sales (Used)', 'Repair', 'Ring light stand',
            'Samsung Earphones', 'Samsung Phones', 'SD CARD', 'Service', 'Sony', 'STK', 'Tablets',
            'TCL', 'Topup', 'Universal TV Remote', 'Vape', 'Watches'
        ];

        $this->colors = ['Black', 'White', 'Midnight', 'Blue', 'Red', 'Space Gray', 'Silver', 'Gold', 'Green', 'Purple'];
    }

    public function openNewManufacturerModal()
    {
        $this->newManufacturerName = '';
        $this->showNewManufacturerModal = true;
    }

    public function saveNewManufacturer()
    {
        if (trim($this->newManufacturerName)) {
            $name = trim($this->newManufacturerName);
            if (!in_array($name, $this->manufacturers)) {
                array_unshift($this->manufacturers, $name);
            }
            $this->form->manufacturer_id = $name;
        }
        $this->showNewManufacturerModal = false;
    }

    public function openNewCategoryModal()
    {
        $this->newCategoryName = '';
        $this->showNewCategoryModal = true;
    }

    public function saveNewCategory()
    {
        if (trim($this->newCategoryName)) {
            $name = trim($this->newCategoryName);
            if (!in_array($name, $this->categories)) {
                array_unshift($this->categories, $name);
            }
            $this->form->category_id = $name;
        }
        $this->showNewCategoryModal = false;
    }

    public function openNewColorModal()
    {
        $this->newColorName = '';
        $this->showNewColorModal = true;
    }

    public function saveNewColor()
    {
        if (trim($this->newColorName)) {
            $name = trim($this->newColorName);
            if (!in_array($name, $this->colors)) {
                array_unshift($this->colors, $name);
            }
            $this->form->color = $name;
        }
        $this->showNewColorModal = false;
    }

    public function save()
    {
        if ($this->isEditMode) {
            $product = $this->form->update();
            $msg = "Product '{$product->name}' updated successfully!";
        } else {
            $product = $this->form->store();
            $msg = "Product '{$product->name}' created successfully!";
        }

        $this->dispatch('show-toast', message: $msg);
        return redirect()->to('/products');
    }

    public function cancel()
    {
        return redirect()->to('/products');
    }

    public function render()
    {
        return view('components.products.⚡product-form');
    }
}
