<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SpeedGridTile;
use App\Models\SpeedGridOption;
use Illuminate\Support\Facades\DB;

class SpeedGridSeeder extends Seeder
{
    public function run(): void
    {
        if (SpeedGridTile::count() > 0) return;

        // Tile 1: iPhone Repairs
        $t1 = SpeedGridTile::create([
            'name' => 'iPhone Screens',
            'bg_color' => 'bg-slate-900',
            'text_color' => 'text-white',
            'sort_order' => 1,
        ]);

        SpeedGridOption::create(['tile_id' => $t1->id, 'label' => 'iPhone 13 Screen', 'price' => 89.99, 'sort_order' => 1]);
        SpeedGridOption::create(['tile_id' => $t1->id, 'label' => 'iPhone 14 Screen', 'price' => 109.99, 'sort_order' => 2]);
        SpeedGridOption::create(['tile_id' => $t1->id, 'label' => 'iPhone 15 Screen', 'price' => 129.99, 'sort_order' => 3]);
        SpeedGridOption::create(['tile_id' => $t1->id, 'label' => 'iPhone 11 Screen', 'price' => 69.99, 'sort_order' => 4]);

        // Tile 2: Samsung Repairs
        $t2 = SpeedGridTile::create([
            'name' => 'Samsung Screens',
            'bg_color' => 'bg-blue-900',
            'text_color' => 'text-white',
            'sort_order' => 2,
        ]);

        SpeedGridOption::create(['tile_id' => $t2->id, 'label' => 'Samsung S22 Screen', 'price' => 119.99, 'sort_order' => 1]);
        SpeedGridOption::create(['tile_id' => $t2->id, 'label' => 'Samsung S23 Screen', 'price' => 139.99, 'sort_order' => 2]);
        SpeedGridOption::create(['tile_id' => $t2->id, 'label' => 'Samsung A54 Screen', 'price' => 79.99, 'sort_order' => 3]);

        // Tile 3: Batteries
        $t3 = SpeedGridTile::create([
            'name' => 'Batteries',
            'bg_color' => 'bg-[#005a43]',
            'text_color' => 'text-white',
            'sort_order' => 3,
        ]);

        SpeedGridOption::create(['tile_id' => $t3->id, 'label' => 'iPhone Battery Swap', 'price' => 45.00, 'sort_order' => 1]);
        SpeedGridOption::create(['tile_id' => $t3->id, 'label' => 'Samsung Battery Swap', 'price' => 50.00, 'sort_order' => 2]);
        SpeedGridOption::create(['tile_id' => $t3->id, 'label' => 'iPad Battery Replacement', 'price' => 65.00, 'sort_order' => 3]);

        // Tile 4: Accessories & Quick Items
        $t4 = SpeedGridTile::create([
            'name' => 'Accessories',
            'bg_color' => 'bg-amber-600',
            'text_color' => 'text-white',
            'sort_order' => 4,
        ]);

        SpeedGridOption::create(['tile_id' => $t4->id, 'label' => 'Case', 'price' => 15.00, 'sort_order' => 1]);
        SpeedGridOption::create(['tile_id' => $t4->id, 'label' => 'Protector Glass', 'price' => 10.00, 'sort_order' => 2]);
        SpeedGridOption::create(['tile_id' => $t4->id, 'label' => 'Cable', 'price' => 12.00, 'sort_order' => 3]);
        SpeedGridOption::create(['tile_id' => $t4->id, 'label' => 'Charger', 'price' => 20.00, 'sort_order' => 4]);
        SpeedGridOption::create(['tile_id' => $t4->id, 'label' => 'Car holder', 'price' => 18.00, 'sort_order' => 5]);
        SpeedGridOption::create(['tile_id' => $t4->id, 'label' => 'Car Charger', 'price' => 15.00, 'sort_order' => 6]);
    }
}
