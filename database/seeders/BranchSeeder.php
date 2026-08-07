<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Business;
use App\Models\Branch;
use App\Models\User;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $business = Business::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'PhoneLab',
                'email' => 'admin@phonelab.com',
                'phone' => '065 682 1234',
                'status' => 'active',
            ]
        );

        $branches = [
            [
                'id' => 1,
                'business_id' => $business->id,
                'name' => 'Phone Lab',
                'slug' => 'phonelab',
                'subdomain' => 'phonelab.clarelab.com',
                'logo_path' => '/storage/branches/phonelab/logo.png',
                'color_primary' => '#1e40af',
                'color_secondary' => '#FFFFFF',
                'invoice_prefix' => 'PHONELAB',
                'invoice_next_number' => 1,
                'receipt_header' => "Phone Lab — Mobile Sales & Repairs",
                'receipt_footer' => "Thank you for choosing Phone Lab!",
                'address' => '1 Main St, Ennis, Co. Clare',
                'phone' => '065 682 1234',
                'email' => 'info@phonelab.com',
                'opening_hours' => 'Mon-Sat: 9:00 AM - 6:00 PM',
                'status' => 'active',
            ],
            [
                'id' => 2,
                'business_id' => $business->id,
                'name' => 'iPear',
                'slug' => 'ipear',
                'subdomain' => 'ipear.clarelab.com',
                'logo_path' => '/storage/branches/ipear/logo.png',
                'color_primary' => '#1DB954',
                'color_secondary' => '#FFFFFF',
                'invoice_prefix' => 'IPEAR',
                'invoice_next_number' => 1,
                'receipt_header' => "iPear — Premium Apple Repairs",
                'receipt_footer' => "Thank you for choosing iPear!",
                'address' => "12 O'Connell St, Ennis, Co. Clare",
                'phone' => '065 684 5678',
                'email' => 'info@ipear.com',
                'opening_hours' => 'Mon-Sat: 9:00 AM - 6:00 PM',
                'status' => 'active',
            ],
            [
                'id' => 3,
                'business_id' => $business->id,
                'name' => 'iPear Tesco',
                'slug' => 'ipeartesco',
                'subdomain' => 'ipeartesco.clarelab.com',
                'logo_path' => '/storage/branches/ipeartesco/logo.png',
                'color_primary' => '#00539F',
                'color_secondary' => '#FFFFFF',
                'invoice_prefix' => 'IPEARTESCO',
                'invoice_next_number' => 1,
                'receipt_header' => "iPear Tesco Kiosk — Express Phone Repairs",
                'receipt_footer' => "Thank you for visiting iPear Tesco!",
                'address' => 'Tesco Shopping Centre, Kilrush Rd, Ennis',
                'phone' => '065 689 9999',
                'email' => 'tesco@ipear.com',
                'opening_hours' => 'Mon-Sun: 9:00 AM - 8:00 PM',
                'status' => 'active',
            ],
            [
                'id' => 4,
                'business_id' => $business->id,
                'name' => 'FIXD',
                'slug' => 'fixd',
                'subdomain' => 'fixd.clarelab.com',
                'logo_path' => '/storage/branches/fixd/logo.png',
                'color_primary' => '#FF6B35',
                'color_secondary' => '#FFFFFF',
                'invoice_prefix' => 'FIXD',
                'invoice_next_number' => 1,
                'receipt_header' => "FIXD — We Fix Everything!",
                'receipt_footer' => "Thank you for choosing FIXD!",
                'address' => '5 Sarsfield St, Limerick',
                'phone' => '061 412 345',
                'email' => 'hello@fixd.com',
                'opening_hours' => 'Mon-Sat: 9:30 AM - 6:00 PM',
                'status' => 'active',
            ],
            [
                'id' => 5,
                'business_id' => $business->id,
                'name' => 'Phone Shop',
                'slug' => 'phoneshop',
                'subdomain' => 'phoneshop.clarelab.com',
                'logo_path' => '/storage/branches/phoneshop/logo.png',
                'color_primary' => '#7c3aed',
                'color_secondary' => '#FFFFFF',
                'invoice_prefix' => 'PHONESHOP',
                'invoice_next_number' => 1,
                'receipt_header' => "Phone Shop — Tech & Mobile Centre",
                'receipt_footer' => "Thank you for visiting Phone Shop!",
                'address' => '8 William St, Limerick',
                'phone' => '061 415 678',
                'email' => 'sales@phoneshop.com',
                'opening_hours' => 'Mon-Sat: 9:00 AM - 6:00 PM',
                'status' => 'active',
            ],
            [
                'id' => 6,
                'business_id' => $business->id,
                'name' => 'Gadgets',
                'slug' => 'gadgets',
                'subdomain' => 'gadgets.clarelab.com',
                'logo_path' => '/storage/branches/gadgets/logo.png',
                'color_primary' => '#dc2626',
                'color_secondary' => '#FFFFFF',
                'invoice_prefix' => 'GADGETS',
                'invoice_next_number' => 1,
                'receipt_header' => "Gadgets — Tech Accessories & Repairs",
                'receipt_footer' => "Thank you for visiting Gadgets!",
                'address' => 'Crescent Shopping Centre, Dooradoyle, Limerick',
                'phone' => '061 300 111',
                'email' => 'info@gadgets.com',
                'opening_hours' => 'Mon-Sun: 9:30 AM - 9:00 PM',
                'status' => 'active',
            ],
        ];

        foreach ($branches as $b) {
            Branch::updateOrCreate(['id' => $b['id']], $b);
        }

        // Clean up old phonelab.com users to prevent database pollution
        User::where('email', 'like', '%@phonelab.com')->delete();

        // Seed Admin & Branch User Accounts
        $users = [
            [
                'email' => 'admin@clarelab.com',
                'name' => 'PhoneLab Admin',
                'password' => Hash::make('Password123!'),
                'business_id' => $business->id,
                'branch_id' => 1,
            ],
            [
                'email' => 'phonelab@clarelab.com',
                'name' => 'Phone Lab Branch Manager',
                'password' => Hash::make('Password123!'),
                'business_id' => $business->id,
                'branch_id' => 1,
            ],
            [
                'email' => 'ipear@clarelab.com',
                'name' => 'iPear Branch Manager',
                'password' => Hash::make('Password123!'),
                'business_id' => $business->id,
                'branch_id' => 2,
            ],
            [
                'email' => 'ipeartesco@clarelab.com',
                'name' => 'iPear Tesco Kiosk Manager',
                'password' => Hash::make('Password123!'),
                'business_id' => $business->id,
                'branch_id' => 3,
            ],
            [
                'email' => 'fixd@clarelab.com',
                'name' => 'FIXD Branch Manager',
                'password' => Hash::make('Password123!'),
                'business_id' => $business->id,
                'branch_id' => 4,
            ],
            [
                'email' => 'phoneshop@clarelab.com',
                'name' => 'Phone Shop Branch Manager',
                'password' => Hash::make('Password123!'),
                'business_id' => $business->id,
                'branch_id' => 5,
            ],
            [
                'email' => 'gadgets@clarelab.com',
                'name' => 'Gadgets Branch Manager',
                'password' => Hash::make('Password123!'),
                'business_id' => $business->id,
                'branch_id' => 6,
            ],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(['email' => $u['email']], $u);
        }
    }
}
