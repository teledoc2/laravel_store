<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;

class ForgotPasswordLivewire extends Component
{
    public function render()
    {
        return view('livewire.auth.forgot-password')->layout('layouts.auth');
    }
}
