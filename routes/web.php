<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\BookingWizard\BookingWizard;
use App\Livewire\PatientAppointments;
use App\Livewire\UserProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['patient.only'])->group(function () {
    Route::get('/', function () {
        return view('home');
    });
    Route::get('/booking', BookingWizard::class)->name('booking');
    Route::get('/appointments', PatientAppointments::class)->name('appointments')->middleware('auth');
    Route::get('/appointments/{appointment}/medical-record', [App\Http\Controllers\MedicalRecordController::class, 'download'])
        ->name('appointments.medical-record')
        ->middleware('auth');
    Route::get('/profile', UserProfile::class)->name('profile')->middleware('auth');
});

Route::get('/login', Login::class)->name('login')->middleware('guest');
Route::get('/register', Register::class)->name('register')->middleware('guest');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout')->middleware('auth');

Route::middleware(['auth', 'patient.only'])->group(function () {
    Route::get('/api/notifications', function () {
        return response()->json([
            'success' => true,
            'data' => auth()->user()->notifications()->latest()->get()
        ]);
    });

    Route::post('/api/notifications/{notification}/read', function (\App\Models\Notification $notification) {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }
        $notification->update(['is_read' => true]);
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.'
        ]);
    });
});
