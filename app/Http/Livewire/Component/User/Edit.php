<?php

namespace App\Http\Livewire\Component\User;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Edit extends Component
{
    public $user;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    public function mount() {
        $this->user = Auth::user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
    }

    public function updateProfile(){
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        if ($this->password) {
            $this->user->update([
                'password' => Hash::make($this->password),
            ]);
        }

        session()->flash('success', 'Profile updated successfully.');
    }

    public function render() {
        return view('livewire.component.user.edit')
                ->layout('manager.layouts.app');
    }
}
