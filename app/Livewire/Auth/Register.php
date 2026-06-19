<?php

namespace App\Livewire\Auth;

use App\Enums\Role;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $dob = '';
    public string $weight = '';
    public string $height = '';

    protected array $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'dob' => 'required|date',
        'weight' => 'required|numeric|min:1|max:500',
        'height' => 'required|numeric|min:1|max:300',
    ];

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => Role::PATIENT,
        ]);

        Patient::create([
            'user_id' => $user->id,
            'dob' => $this->dob,
            'weight' => $this->weight,
            'height' => $this->height,
        ]);

        Auth::login($user);

        return $this->redirect('/');
    }

    public function render()
    {
        return view('livewire.auth.register')
            ->layout('components.layouts.app');
    }
}
