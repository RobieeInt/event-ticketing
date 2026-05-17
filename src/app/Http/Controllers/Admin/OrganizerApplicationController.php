<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizerApplication;
use Illuminate\Http\Request;

class OrganizerApplicationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $applications = OrganizerApplication::with('user')
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15);

        return view('admin.organizer.index', compact('applications', 'status'));
    }

    public function approve(Request $request, OrganizerApplication $application)
    {
        if ($application->status !== 'pending') {
            return back()->withErrors(['msg' => 'Pengajuan ini sudah diproses sebelumnya.']);
        }

        $application->update([
            'status'      => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => $request->user()->id,
            'review_note' => null,
        ]);

        $application->user->update(['role' => 'organizer']);

        return back()->with('status', "Pengajuan {$application->user->name} disetujui. Role diubah jadi organizer.");
    }

    public function reject(Request $request, OrganizerApplication $application)
    {
        $request->validate([
            'review_note' => ['required', 'string', 'max:1000'],
        ]);

        if ($application->status !== 'pending') {
            return back()->withErrors(['msg' => 'Pengajuan ini sudah diproses sebelumnya.']);
        }

        $application->update([
            'status'      => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => $request->user()->id,
            'review_note' => $request->review_note,
        ]);

        return back()->with('status', "Pengajuan {$application->user->name} ditolak.");
    }

    public function suspend(Request $request, OrganizerApplication $application)
    {
        $request->validate([
            'suspension_reason' => ['required', 'string', 'max:1000'],
        ]);

        $application->user->update([
            'suspended_at'      => now(),
            'suspension_reason' => $request->suspension_reason,
        ]);

        return back()->with('status', "Akun {$application->user->name} telah disuspend.");
    }

    public function unsuspend(Request $request, OrganizerApplication $application)
    {
        $application->user->update([
            'suspended_at'      => null,
            'suspension_reason' => null,
        ]);

        return back()->with('status', "Akun {$application->user->name} telah diaktifkan kembali.");
    }
}
