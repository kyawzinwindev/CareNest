<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CareNest Medical Record</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 14px;
            line-height: 1.5;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #06b6d4;
            padding-bottom: 15px;
        }
        .clinic-name {
            font-size: 24px;
            font-weight: bold;
            color: #0891b2;
            margin: 0;
        }
        .clinic-sub {
            font-size: 11px;
            color: #64748b;
            margin: 2px 0 0 0;
        }
        .doc-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 25px;
            color: #0f172a;
            text-transform: uppercase;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .meta-table td {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
        }
        .meta-label {
            font-weight: bold;
            color: #475569;
            width: 25%;
            background-color: #f8fafc;
        }
        .meta-value {
            color: #0f172a;
            width: 25%;
        }
        .section-heading {
            font-size: 14px;
            font-weight: bold;
            color: #0891b2;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        .prescription-box {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 18px;
            white-space: pre-wrap;
            color: #0f172a;
            font-size: 13px;
            min-height: 150px;
            line-height: 1.6;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="clinic-name">CareNest Medical Clinic</div>
        <div class="clinic-sub">123 Health Avenue, Silom, Bangkok 10500 | Tel: +66 2 123 4567 | info@carenest.com</div>
    </div>

    <div class="doc-title">Official Medical Record & Prescription</div>

    <table class="meta-table">
        <tr>
            <td class="meta-label">Appointment ID</td>
            <td class="meta-value">#{{ $appointment->id }}</td>
            <td class="meta-label">Consultation Date</td>
            <td class="meta-value">{{ \Carbon\Carbon::parse($appointment->time_slot->schedule->date)->format('M d, Y') }}</td>
        </tr>
        <tr>
            <td class="meta-label">Patient Name</td>
            <td class="meta-value">{{ $appointment->patient->user->name }}</td>
            <td class="meta-label">Date of Birth</td>
            <td class="meta-value">{{ $appointment->patient->dob ? $appointment->patient->dob->format('M d, Y') : 'N/A' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Attending Doctor</td>
            <td class="meta-value">Dr. {{ $appointment->doctor->user->name }}</td>
            <td class="meta-label">Service / Treatment</td>
            <td class="meta-value">{{ $appointment->service->name }}</td>
        </tr>
        <tr>
            <td class="meta-label">Status</td>
            <td class="meta-value" style="color: #15803d; font-weight: bold;">Finished</td>
            <td class="meta-label">Printed Date</td>
            <td class="meta-value">{{ now()->format('M d, Y H:i') }}</td>
        </tr>
    </table>

    <div class="section-heading">Doctor's Clinical Notes & Prescription</div>
    <div class="prescription-box">{{ $appointment->prescription ?: 'No clinical notes recorded.' }}</div>

    <div style="margin-top: 60px; width: 100%;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; width: 50%; text-align: left; vertical-align: bottom;">
                    <div style="font-size: 12px; color: #64748b;">
                        Patient Signature: _______________________
                    </div>
                </td>
                <td style="border: none; width: 50%; text-align: right; vertical-align: bottom;">
                    <div style="font-size: 12px; color: #64748b; margin-bottom: 5px;">
                        Authorized By:
                    </div>
                    <div style="font-weight: bold; color: #0f172a;">
                        Dr. {{ $appointment->doctor->user->name }}
                    </div>
                    <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                        Attending Medical Practitioner
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        This document is an official medical record generated electronically by the CareNest Clinic System.
        <br>
        &copy; {{ date('Y') }} CareNest Clinic. All rights reserved.
    </div>

</body>
</html>
