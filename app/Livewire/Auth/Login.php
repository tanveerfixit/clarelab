<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use App\Services\BranchContext;

#[Layout('components.layouts.guest', ['title' => 'Central Login / Branch Dashboard'])]
class Login extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required')]
    public string $password = '';

    public bool $remember = false;

    public ?string $errorMessage = null;

    // Human verification (2-digit calculation)
    public int $num1;
    public int $num2;
    public ?string $verification_answer = null;

    public function mount()
    {
        if (Auth::check()) {
            return redirect()->to('/register');
        }

        $this->generateVerification();
    }

    protected function generateVerification()
    {
        $this->num1 = rand(1, 9);
        $this->num2 = rand(1, 9);
        $this->verification_answer = null;
    }

    public function login()
    {
        $this->validate();
        $this->errorMessage = null;

        // Verify human calculation
        if (is_null($this->verification_answer) || (int)$this->verification_answer !== ($this->num1 + $this->num2)) {
            $this->errorMessage = 'Human verification failed. Please try again.';
            $this->generateVerification();
            return;
        }

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            $this->dispatch('show-toast', message: 'Signed in successfully!');

            // Set active branch context dynamically from logged-in user
            $user = Auth::user();
            if ($user->branch_id) {
                session([
                    'active_branch_id' => $user->branch_id,
                    'active_branch' => $user->branch ?? \App\Models\Branch::find($user->branch_id),
                ]);
            }

            return redirect()->intended('/register');
        }

        $this->errorMessage = 'Invalid email address or password.';
        $this->generateVerification();
    }

    public function render()
    {
        return view('components.auth.⚡login', [
            'branchName' => BranchContext::name(),
            'branchAddress' => BranchContext::address(),
        ]);
    }
}
