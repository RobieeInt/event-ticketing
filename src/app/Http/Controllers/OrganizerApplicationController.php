<?php

namespace App\Http\Controllers;

use App\Models\OrganizerApplication;
use Illuminate\Http\Request;

class OrganizerApplicationController extends Controller
{
    public function create(Request $request)
    {
        $application = $request->user()->organizerApplication;
        return view('organizer.apply', compact('application'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_name' => ['nullable','string','max:255'],
            'phone' => ['nullable','string','max:20'],
            'reason' => ['nullable','string','max:2000'],
        ]);

        OrganizerApplication::updateOrCreate(
            ['user_id' => $request->user()->id],
            array_merge($data, ['status' => 'pending'])
        );

        return redirect()
            ->route('dashboard')
            ->with('status', 'Pengajuan organizer berhasil. Menunggu approval admin.');
    }
}
