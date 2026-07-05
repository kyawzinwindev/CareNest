@component('mail::message')
# Appointment Cancelled

Dear {{ $appointment->patient->user->name }},

We are writing to confirm that your appointment at CareNest has been cancelled. Below are the details of the cancelled appointment:

- **Service / Treatment**: {{ $appointment->service->name }}
- **Consulting Doctor**: Dr. {{ $appointment->doctor->user->name }}
- **Date**: {{ \Carbon\Carbon::parse($appointment->time_slot->schedule->date)->format('M d, Y') }}
- **Time**: {{ $appointment->time_slot->start_time }} - {{ $appointment->time_slot->end_time }}
- **Status**: Cancelled

**Refund Information**:
A full refund will be processed and credited back to your account or payment method within 3-5 business days.

If you did not request this cancellation or have any questions, please contact our clinic support immediately.

Thanks,  
{{ config('app.name') }} Team
@endcomponent
