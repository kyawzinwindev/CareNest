<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Enums\AppointmentStatus;
use App\Enums\Role;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class MedicalRecordController extends Controller
{
    public function download(Appointment $appointment)
    {
        // 1. Ensure user is authenticated
        if (!Auth::check()) {
            abort(401, 'Unauthenticated.');
        }

        $user = Auth::user();

        // 2. Validate role and ownership (only owner patient can download)
        if ($user->role !== Role::PATIENT || !$user->patient || $user->patient->id !== $appointment->patient_id) {
            abort(403, 'Unauthorized access to this medical record.');
        }

        // 3. Validate appointment status is Finished
        if ($appointment->status !== AppointmentStatus::FINISHED) {
            abort(400, 'Medical record is only available for completed appointments.');
        }

        // 4. Load required relationships
        $appointment->load(['doctor.user', 'patient.user', 'service']);

        // 5. Compile HTML and generate PDF
        $pdf = Pdf::loadView('medical_record_pdf', compact('appointment'));

        // 6. Download the PDF file
        return $pdf->download("CareNest_Medical_Record_{$appointment->id}.pdf");
    }
}
