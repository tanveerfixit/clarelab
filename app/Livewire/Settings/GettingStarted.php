<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Services\BranchContext;
use App\Models\Business;

class GettingStarted extends Component
{
    public string $activeTab = 'account-setup';

    // Account Setup Fields
    public string $currency = 'EUR';
    public string $timezone = 'Europe/London';
    public string $date_format = 'DD-MM-YY';
    public string $time_format = '12 hour';
    public string $language = 'English';

    // Company Info Fields
    public string $subdomain = '';
    public string $company_name = '';
    public string $company_phone = '';
    public string $company_email = '';
    public string $street_address = '';
    public string $city = '';
    public string $state = '';
    public string $zip_code = '';
    public string $country = '';

    // Label Printer Fields
    public string $label_size = 'dymo_30334';
    public int $barcode_length = 20;
    public int $margin_top = 8;
    public int $margin_left = 3;
    public int $margin_bottom = 3;
    public int $margin_right = 3;
    public string $orientation = 'Landscape';
    public string $font_size = 'Large';
    public string $font_family = 'Arial Black';

    // Alerts
    public ?string $successMessage = null;

    protected function rules()
    {
        if ($this->activeTab === 'account-setup') {
            return [
                'currency' => 'required|string|max:50',
                'timezone' => 'required|string|max:100',
                'date_format' => 'required|string|max:50',
                'time_format' => 'required|string|max:50',
                'language' => 'required|string|max:50',
            ];
        }

        return [
            'company_name' => 'required|string|max:255',
            'company_phone' => 'required|string|max:50',
            'company_email' => 'required|email|max:255',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:50',
            'country' => 'required|string|max:100',
        ];
    }

    public function mount()
    {
        $business = $this->getBusiness();
        if ($business) {
            $this->currency = $business->currency ?? 'EUR';
            $this->timezone = $business->timezone ?? 'Europe/London';
            $this->date_format = $business->date_format ?? 'DD-MM-YY';
            $this->time_format = $business->time_format ?? '12 hour';
            $this->language = $business->language ?? 'English';

            $this->company_name = $business->name ?? '';
            $this->company_phone = $business->phone ?? '';
            $this->company_email = $business->email ?? '';
            $this->street_address = $business->street_address ?? '';
            $this->city = $business->city ?? '';
            $this->state = $business->state ?? '';
            $this->zip_code = $business->zip_code ?? '';
            $this->country = $business->country ?? '';

            $this->label_size = $business->label_size ?? 'dymo_30334';
            $this->barcode_length = (int)($business->barcode_length ?? 20);
            $this->margin_top = (int)($business->margin_top ?? 8);
            $this->margin_left = (int)($business->margin_left ?? 3);
            $this->margin_bottom = (int)($business->margin_bottom ?? 3);
            $this->margin_right = (int)($business->margin_right ?? 3);
            $this->orientation = $business->orientation ?? 'Landscape';
            $this->font_size = $business->font_size ?? 'Large';
            $this->font_family = $business->font_family ?? 'Arial Black';
        }

        $branch = BranchContext::current();
        if ($branch) {
            $this->subdomain = $branch->subdomain ?? $branch->slug ?? 'localhost';
        }
    }

    public function getBusiness(): ?Business
    {
        return BranchContext::current()?->business ?? Business::first();
    }

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
        $this->successMessage = null;
    }

    public function saveAccountSetup()
    {
        $this->validate([
            'currency' => 'required|string|max:50',
            'timezone' => 'required|string|max:100',
            'date_format' => 'required|string|max:50',
            'time_format' => 'required|string|max:50',
            'language' => 'required|string|max:50',
        ]);

        $business = $this->getBusiness();
        if ($business) {
            $business->update([
                'currency' => $this->currency,
                'timezone' => $this->timezone,
                'date_format' => $this->date_format,
                'time_format' => $this->time_format,
                'language' => $this->language,
            ]);
            $this->successMessage = 'Account settings saved successfully!';
        } else {
            $this->successMessage = 'Error: Business not found.';
        }
    }

    public function saveCompanyInfo()
    {
        $this->validate([
            'company_name' => 'required|string|max:255',
            'company_phone' => 'required|string|max:50',
            'company_email' => 'required|email|max:255',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:50',
            'country' => 'required|string|max:100',
        ]);

        $business = $this->getBusiness();
        if ($business) {
            $business->update([
                'name' => $this->company_name,
                'phone' => $this->company_phone,
                'email' => $this->company_email,
                'street_address' => $this->street_address,
                'city' => $this->city,
                'state' => $this->state,
                'zip_code' => $this->zip_code,
                'country' => $this->country,
            ]);

            // Update active branch name if it matches or needs sync (optional, but good practice)
            $branch = BranchContext::current();
            if ($branch && $branch->name === $business->getOriginal('name')) {
                $branch->update(['name' => $this->company_name]);
            }

            $this->successMessage = 'Company information saved successfully!';
        } else {
            $this->successMessage = 'Error: Business not found.';
        }
    }

    public function saveLabelPrinter()
    {
        $this->validate([
            'label_size' => 'required|string|max:100',
            'barcode_length' => 'required|integer|min:1|max:100',
            'margin_top' => 'required|integer|min:0|max:100',
            'margin_left' => 'required|integer|min:0|max:100',
            'margin_bottom' => 'required|integer|min:0|max:100',
            'margin_right' => 'required|integer|min:0|max:100',
            'orientation' => 'required|string|max:50',
            'font_size' => 'required|string|max:50',
            'font_family' => 'required|string|max:100',
        ]);

        $business = $this->getBusiness();
        if ($business) {
            $business->update([
                'label_size' => $this->label_size,
                'barcode_length' => $this->barcode_length,
                'margin_top' => $this->margin_top,
                'margin_left' => $this->margin_left,
                'margin_bottom' => $this->margin_bottom,
                'margin_right' => $this->margin_right,
                'orientation' => $this->orientation,
                'font_size' => $this->font_size,
                'font_family' => $this->font_family,
            ]);

            $this->successMessage = 'Label printer settings saved successfully!';
        } else {
            $this->successMessage = 'Error: Business not found.';
        }
    }

    public function render()
    {
        return view('livewire.settings.getting-started')
            ->layout('components.layouts.app', ['title' => 'Getting Started']);
    }
}
