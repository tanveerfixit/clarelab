<?php

namespace App\Livewire\Repairs;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app', ['header' => 'Repairs'])]
class RepairBooking extends Component
{
    // Customer Info
    public string $customer_name = '';
    public string $phone_number = '';
    public string $email_address = '';

    // Device & Issue Info
    public string $device_model = '';
    public string $problem_description = '';
    public string $part_needed = '';

    // Payment & Quote Info
    public ?float $total_quote = 0.00;
    public ?float $deposit_paid = 0.00;

    // Mobile Intake Session Details
    public string $session_id = 'INT-P0ZV2U';

    // Navigation Tab State ('new_booking' or 'old_jobs')
    public string $activeTab = 'new_booking';

    // Mock Old Repair Jobs List (Simple structure for viewing past repair jobs)
    public array $oldJobs = [
        [
            'job_id' => 'REP-9081',
            'customer_name' => 'John Doe',
            'phone' => '087 123 4567',
            'device' => 'iPhone 13 Pro',
            'issue' => 'Screen glass cracked & OLED touch non-responsive',
            'status' => 'Completed',
            'total_quote' => 120.00,
            'deposit' => 20.00,
            'date' => '30-07-2026',
        ],
        [
            'job_id' => 'REP-9078',
            'customer_name' => 'Sarah Jenkins',
            'phone' => '085 987 6543',
            'device' => 'Samsung S22 Ultra',
            'issue' => 'Battery replacement & charging port cleaning',
            'status' => 'In Progress',
            'total_quote' => 85.00,
            'deposit' => 30.00,
            'date' => '29-07-2026',
        ],
        [
            'job_id' => 'REP-9072',
            'customer_name' => 'Michael Smith',
            'phone' => '086 555 9123',
            'device' => 'iPad Air 4th Gen',
            'issue' => 'Liquid damage diagnosis',
            'status' => 'Pending Parts',
            'total_quote' => 150.00,
            'deposit' => 50.00,
            'date' => '28-07-2026',
        ],
    ];

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    public function mount()
    {
        $this->generateSessionId();
    }

    public function generateSessionId()
    {
        $this->session_id = 'INT-' . strtoupper(substr(md5(uniqid()), 0, 6));
    }

    public function getRemainingBalanceProperty(): float
    {
        $quote = floatval($this->total_quote ?: 0.00);
        $deposit = floatval($this->deposit_paid ?: 0.00);
        return max(0.00, round($quote - $deposit, 2));
    }

    public function saveBooking(bool $andPrint = false)
    {
        $this->validate([
            'customer_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:50',
            'email_address' => 'nullable|email|max:255',
            'device_model' => 'required|string|max:255',
            'problem_description' => 'required|string|max:1000',
            'total_quote' => 'nullable|numeric|min:0',
            'deposit_paid' => 'nullable|numeric|min:0',
        ]);

        $actionText = $andPrint ? "saved and printed" : "saved";
        $this->dispatch('show-toast', message: "Repair booking #{$this->session_id} {$actionText} successfully!");

        // Reset form after saving
        $this->reset(['customer_name', 'phone_number', 'email_address', 'device_model', 'problem_description', 'total_quote', 'deposit_paid']);
        $this->generateSessionId();
    }

    public function render()
    {
        return view('components.repairs.⚡repair-booking');
    }
}
