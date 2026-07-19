<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\Blog;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\User;
use App\Policies\AppointmentPolicy;
use App\Policies\BlogPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\SchedulePolicy;
use App\Policies\ServicePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (!class_exists('Filament\Tables\Actions\EditAction')) {
            class_alias(\Filament\Actions\EditAction::class, 'Filament\Tables\Actions\EditAction');
        }
        if (!class_exists('Filament\Tables\Actions\DeleteAction')) {
            class_alias(\Filament\Actions\DeleteAction::class, 'Filament\Tables\Actions\DeleteAction');
        }
        if (!class_exists('Filament\Tables\Actions\DeleteBulkAction')) {
            class_alias(\Filament\Actions\DeleteBulkAction::class, 'Filament\Tables\Actions\DeleteBulkAction');
        }
        if (!class_exists('Filament\Tables\Actions\BulkActionGroup')) {
            class_alias(\Filament\Actions\BulkActionGroup::class, 'Filament\Tables\Actions\BulkActionGroup');
        }
        if (!class_exists('Filament\Tables\Actions\Action')) {
            class_alias(\Filament\Actions\Action::class, 'Filament\Tables\Actions\Action');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Schedule::class, SchedulePolicy::class);
        Gate::policy(Appointment::class, AppointmentPolicy::class);
        Gate::policy(Payment::class, PaymentPolicy::class);
        Gate::policy(Service::class, ServicePolicy::class);
        Gate::policy(Blog::class, BlogPolicy::class);

        Payment::observe(\App\Observers\PaymentObserver::class);
        Appointment::observe(\App\Observers\AppointmentObserver::class);
    }
}


