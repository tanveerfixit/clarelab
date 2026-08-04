<?php

namespace App\Livewire\Repairs;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\RepairTicket;
use App\Services\BranchContext;
use Illuminate\Support\Facades\DB;

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
    public string $session_id = '';

    // Navigation Tab State ('new_booking' or 'old_jobs')
    public string $activeTab = 'new_booking';

    // Search Query for Repair History
    public string $searchQuery = '';

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    public function mount()
    {
        $this->generateSessionId();
        $this->seedInitialTicketsIfEmpty();
    }

    public function generateSessionId()
    {
        $this->session_id = 'REP-' . rand(1000, 9999);
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
            'part_needed' => 'nullable|string|max:255',
            'total_quote' => 'nullable|numeric|min:0',
            'deposit_paid' => 'nullable|numeric|min:0',
        ]);

        $branch = BranchContext::current();

        $ticket = RepairTicket::create([
            'business_id' => $branch?->business_id ?: 1,
            'branch_id' => $branch?->id ?: 1,
            'ticket_number' => $this->session_id,
            'customer_name' => $this->customer_name,
            'phone_number' => $this->phone_number,
            'email_address' => $this->email_address,
            'device_model' => $this->device_model,
            'problem_description' => $this->problem_description,
            'part_needed' => $this->part_needed,
            'total_quote' => floatval($this->total_quote ?: 0.00),
            'deposit_paid' => floatval($this->deposit_paid ?: 0.00),
            'status' => 'Received',
        ]);

        $actionText = $andPrint ? "saved to database & sent to thermal printer" : "saved to database";
        $this->dispatch('show-toast', message: "Repair Ticket #{$ticket->ticket_number} for {$ticket->customer_name} {$actionText} successfully!");

        // Switch to history tab to show the newly saved repair job immediately
        $this->activeTab = 'old_jobs';

        // Reset form after saving
        $this->reset(['customer_name', 'phone_number', 'email_address', 'device_model', 'problem_description', 'part_needed', 'total_quote', 'deposit_paid']);
        $this->generateSessionId();
    }

    private function seedInitialTicketsIfEmpty()
    {
        if (RepairTicket::count() === 0) {
            $branchId = BranchContext::current()?->id ?: 1;
            $businessId = BranchContext::current()?->business_id ?: 1;

            RepairTicket::create([
                'business_id' => $businessId,
                'branch_id' => $branchId,
                'ticket_number' => 'REP-9081',
                'customer_name' => 'John Doe',
                'phone_number' => '087 123 4567',
                'email_address' => 'john@example.com',
                'device_model' => 'iPhone 13 Pro',
                'problem_description' => 'Screen glass cracked & OLED touch non-responsive',
                'part_needed' => 'iPhone 13 Pro OLED Screen',
                'total_quote' => 120.00,
                'deposit_paid' => 20.00,
                'status' => 'Completed',
                'created_at' => now()->subDays(2),
            ]);

            RepairTicket::create([
                'business_id' => $businessId,
                'branch_id' => $branchId,
                'ticket_number' => 'REP-9078',
                'customer_name' => 'Sarah Jenkins',
                'phone_number' => '085 987 6543',
                'email_address' => 'sarah@example.com',
                'device_model' => 'Samsung S22 Ultra',
                'problem_description' => 'Battery replacement & charging port cleaning',
                'part_needed' => 'Samsung S22 Ultra Battery',
                'total_quote' => 85.00,
                'deposit_paid' => 30.00,
                'status' => 'In Progress',
                'created_at' => now()->subDays(1),
            ]);

            RepairTicket::create([
                'business_id' => $businessId,
                'branch_id' => $branchId,
                'ticket_number' => 'REP-9072',
                'customer_name' => 'Michael Smith',
                'phone_number' => '086 555 9123',
                'email_address' => 'michael@example.com',
                'device_model' => 'iPad Air 4th Gen',
                'problem_description' => 'Liquid damage diagnosis',
                'part_needed' => 'Diagnostic Inspection',
                'total_quote' => 150.00,
                'deposit_paid' => 50.00,
                'status' => 'Pending Parts',
                'created_at' => now()->subHours(6),
            ]);
        }
    }

    public function render()
    {
        $branchId = BranchContext::current()?->id ?: 1;

        $query = RepairTicket::where('branch_id', $branchId);

        if (!empty(trim($this->searchQuery))) {
            $term = '%' . trim($this->searchQuery) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('ticket_number', 'like', $term)
                  ->orWhere('customer_name', 'like', $term)
                  ->orWhere('phone_number', 'like', $term)
                  ->orWhere('device_model', 'like', $term)
                  ->orWhere('problem_description', 'like', $term);
            });
        }

        $jobs = $query->orderBy('id', 'desc')->get();
        $totalJobsCount = RepairTicket::where('branch_id', $branchId)->count();

        return view('components.repairs.⚡repair-booking', [
            'jobs' => $jobs,
            'totalJobsCount' => $totalJobsCount,
        ]);
    }
}
