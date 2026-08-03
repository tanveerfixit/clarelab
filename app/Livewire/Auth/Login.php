<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use App\Services\BranchContext;

#[Layout('components.layouts.guest', ['title' => 'Sign In - Phone Lab EPOS'])]
class Login extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required')]
    public string $password = '';

    public bool $remember = false;

    public ?string $errorMessage = null;

    public function login()
    {
        $this->validate();
        $this->errorMessage = null;

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            
            $this->dispatch('show-toast', message: 'Signed in successfully!');
            return redirect()->intended('/register');
        }

        $this->errorMessage = 'Invalid email address or password.';
    }

    public function render()
    {
        return view('components.auth.⚡login', [
            'branchName' => BranchContext::name(),
            'branchAddress' => BranchContext::address(),
        ]);
    }
}
