@component('mail::message')
# Appointment Confirmed

Dear {{ $appointment->patient->user->name }},

We are pleased to inform you that your appointment at CareNest has been confirmed. Below are the appointment details:

- **Service / Treatment**: {{ $appointment->service->name }}
- **Consulting Doctor**: Dr. {{ $appointment->doctor->user->name }}
- **Date**: {{ \Carbon\Carbon::parse($appointment->time_slot->schedule->date)->format('M d, Y') }}
- **Time**: {{ $appointment->time_slot->start_time }} - {{ $appointment->time_slot->end_time }}
- **Status**: Confirmed

If you need to reschedule or cancel your appointment, please contact the clinic at least 24 hours in advance.

Thanks,  
{{ config('app.name') }} Team
@endcomponent
