<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\RepairTicket;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Customer Records
        $customers = [
            [
                'id' => 1,
                'business_id' => 1,
                'name' => 'Walk-in Customer',
                'slug' => 'walk-in-customer',
                'company' => null,
                'phone' => null,
                'secondary_phone' => null,
                'email' => null,
            ],
            [
                'id' => 2,
                'business_id' => 1,
                'name' => 'paul tighe',
                'slug' => 'paul-tighe',
                'company' => null,
                'phone' => '0872504432',
                'secondary_phone' => null,
                'email' => 'paul.tighe@gmail.com',
            ],
            [
                'id' => 3,
                'business_id' => 1,
                'name' => 'Mike Collins',
                'slug' => 'mike-collins',
                'company' => 'Collins Repairs',
                'phone' => '0833769100',
                'secondary_phone' => null,
                'email' => 'mike.collins@outlook.com',
            ],
            [
                'id' => 4,
                'business_id' => 1,
                'name' => "sinead o'dea",
                'slug' => 'sinead-odea',
                'company' => null,
                'phone' => '0851378276',
                'secondary_phone' => null,
                'email' => 'sinead.odea@yahoo.com',
            ],
            [
                'id' => 5,
                'business_id' => 1,
                'name' => 'sharon',
                'slug' => 'sharon',
                'company' => null,
                'phone' => '0851943255',
                'secondary_phone' => null,
                'email' => 'sharon.pos@example.com',
            ],
        ];

        foreach ($customers as $c) {
            Customer::updateOrCreate(['id' => $c['id']], $c);
        }

        // 2. Seed Sample Repair Tickets for Customers
        $repairs = [
            [
                'business_id' => 1,
                'branch_id' => 1,
                'customer_id' => 2, // paul tighe
                'ticket_number' => 'REP-2026-0001',
                'customer_name' => 'paul tighe',
                'phone_number' => '0872504432',
                'email_address' => 'paul.tighe@gmail.com',
                'device_model' => 'iPhone 13 Pro',
                'problem_description' => 'Broken Screen Replacement',
                'part_needed' => 'iPhone 13 Pro OLED Screen',
                'total_quote' => 149.99,
                'deposit_paid' => 20.00,
                'status' => 'In Progress',
            ],
            [
                'business_id' => 1,
                'branch_id' => 1,
                'customer_id' => 4, // sinead o'dea
                'ticket_number' => 'REP-2026-0002',
                'customer_name' => "sinead o'dea",
                'phone_number' => '0851378276',
                'email_address' => 'sinead.odea@yahoo.com',
                'device_model' => 'Samsung Galaxy S22 Ultra',
                'problem_description' => 'Rear Glass Cracked & Battery Drain',
                'part_needed' => 'Galaxy S22 Back Glass & Battery',
                'total_quote' => 110.00,
                'deposit_paid' => 0.00,
                'status' => 'Received',
            ],
        ];

        foreach ($repairs as $r) {
            RepairTicket::updateOrCreate(['ticket_number' => $r['ticket_number']], $r);
        }

        // 3. Seed Sample Invoices for Customers
        $product = Product::first();
        $productId = $product ? $product->id : 1;
        $productName = $product ? $product->name : 'Standard Screen repair';

        $invoices = [
            [
                'id' => 1,
                'business_id' => 1,
                'branch_id' => 1,
                'customer_id' => 3, // Mike Collins
                'invoice_number' => 'INV-2026-0001',
                'subtotal' => 79.99,
                'discount' => 0.00,
                'tax_amount' => 0.00,
                'grand_total' => 79.99,
                'payment_method' => 'Card',
            ],
            [
                'id' => 2,
                'business_id' => 1,
                'branch_id' => 1,
                'customer_id' => 5, // sharon
                'invoice_number' => 'INV-2026-0002',
                'subtotal' => 45.00,
                'discount' => 5.00,
                'tax_amount' => 0.00,
                'grand_total' => 40.00,
                'payment_method' => 'Cash',
            ],
        ];

        foreach ($invoices as $inv) {
            $createdInvoice = Invoice::updateOrCreate(['id' => $inv['id']], $inv);

            // Add Invoice Items
            InvoiceItem::updateOrCreate(
                ['invoice_id' => $createdInvoice->id],
                [
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'unit_price' => $inv['subtotal'],
                    'quantity' => 1,
                    'total' => $inv['subtotal'],
                ]
            );
        }
    }
}
