<?php

namespace App\Livewire;

use Livewire\Component;

class HomeDashboard extends Component
{
    public function render()
    {
        return view('components.⚡home-dashboard')
            ->layout('components.layouts.app', ['header' => 'Dashboard']);
    }
}
