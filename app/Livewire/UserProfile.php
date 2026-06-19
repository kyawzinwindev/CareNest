<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Carbon\Carbon;

class UserProfile extends Component
{
    // Profile Fields
    public string $name = '';
    public string $email = '';
    public string $dob = '';
    public string $weight = '';
    public string $height = '';

    // Security Fields
    public string $currentPassword = '';
    public string $newPassword = '';
    public string $confirmNewPassword = '';

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;

        $patient = $user->patient;
        if ($patient) {
            $this->dob = $patient->dob ? Carbon::parse($patient->dob)->format('Y-m-d') : '';
            $this->weight = $patient->weight ?? '';
            $this->height = $patient->height ?? '';
        }
    }

    public function updateProfile()
    {
        $user = Auth::user();
        
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'dob' => 'required|date',
            'weight' => 'required|numeric|min:1|max:500',
            'height' => 'required|numeric|min:1|max:300',
        ]);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $patient = $user->patient;
        if (!$patient) {
            Patient::create([
                'user_id' => $user->id,
                'dob' => $this->dob,
                'weight' => $this->weight,
                'height' => $this->height,
            ]);
        } else {
            $patient->update([
                'dob' => $this->dob,
                'weight' => $this->weight,
                'height' => $this->height,
            ]);
        }

        session()->flash('profile_success', 'Profile metrics updated successfully.');
    }

    public function updatePassword()
    {
        $user = Auth::user();

        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8',
            'confirmNewPassword' => 'required|same:newPassword',
        ]);

        if (!Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'The current password does not match.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->newPassword),
        ]);

        $this->currentPassword = '';
        $this->newPassword = '';
        $this->confirmNewPassword = '';

        session()->flash('password_success', 'Password updated successfully.');
    }

    public function render()
    {
        return view('livewire.user-profile')
            ->layout('components.layouts.app');
    }
}
