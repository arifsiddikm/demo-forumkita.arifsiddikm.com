<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with(['reporter', 'reportable', 'resolvedBy'])
            ->latest()
            ->paginate(20);
        return view('admin.reports.index', compact('reports'));
    }

    public function resolve(Request $request, Report $report)
    {
        $request->validate(['admin_note' => 'nullable|string|max:500']);
        $report->update([
            'status'      => 'reviewed',
            'admin_note'  => $request->admin_note,
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);
        return back()->with('success', 'Laporan berhasil diselesaikan.');
    }

    public function dismiss(Report $report)
    {
        $report->update([
            'status'      => 'dismissed',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);
        return back()->with('success', 'Laporan berhasil diabaikan.');
    }
}
