<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Resources\PresenceResource;

class PresenceController extends Controller
{
    public function index(Request $request)
    {
        $query = Presence::with(['createdBy', 'store', 'shiftStore']);

        if ($request->has('date')) {
            $date = Carbon::parse($request->date);
            $query->whereDate('check_in', $date);
        }

        if ($request->has('employee_id')) {
            $query->where('created_by_id', $request->employee_id);
        }

        if ($request->has('store_id')) {
            $query->where('store_id', $request->store_id);
        }

        $presences = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => PresenceResource::collection($presences)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'shift_store_id' => 'required|exists:shift_stores,id',
        ]);

        $presence = new Presence();
        $presence->store_id = $request->store_id;
        $presence->shift_store_id = $request->shift_store_id;
        $presence->check_in = now();
        $presence->created_by_id = auth()->id();
        $presence->save();

        return response()->json([
            'success' => true,
            'message' => 'Check-in recorded successfully',
            'data' => $presence->load(['createdBy', 'store', 'shiftStore'])
        ], 201);
    }

    public function show(Presence $presence)
    {
        return response()->json([
            'success' => true,
            'data' => $presence->load(['createdBy', 'approvedBy', 'store', 'shiftStore', 'storeOut'])
        ]);
    }

    public function checkOut(Presence $presence, Request $request)
    {
        if ($presence->check_out) {
            return response()->json([
                'success' => false,
                'message' => 'Already checked out'
            ], 422);
        }

        $request->validate([
            'store_out_id' => 'required|exists:stores,id',
        ]);

        $presence->check_out = now();
        $presence->store_out_id = $request->store_out_id;
        $presence->save();

        // Calculate penalties and effective working time
        $checkOutStatus = $presence->getCheckOutStatus();
        $totalPenalty = $presence->calculateTotalPenalty();
        $effectiveWorkingTime = $presence->calculateEffectiveWorkingTime();
        $dailySalary = $presence->calculateDailySalary();

        return response()->json([
            'success' => true,
            'message' => 'Check-out recorded successfully',
            'data' => [
                'presence' => $presence->load(['createdBy', 'store', 'shiftStore', 'storeOut']),
                'check_out_status' => $checkOutStatus,
                'total_penalty_hours' => $totalPenalty,
                'effective_working_hours' => $effectiveWorkingTime,
                'daily_salary' => $dailySalary
            ]
        ]);
    }

    public function approve(Presence $presence)
    {
        if ($presence->approved_by_id) {
            return response()->json([
                'success' => false,
                'message' => 'Presence already approved'
            ], 422);
        }

        $presence->approved_by_id = auth()->id();
        $presence->approved_at = now();
        $presence->save();

        return response()->json([
            'success' => true,
            'message' => 'Presence approved successfully',
            'data' => $presence->load(['createdBy', 'approvedBy', 'store', 'shiftStore', 'storeOut'])
        ]);
    }

    public function report(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'employee_id' => 'nullable|exists:users,id',
            'store_id' => 'nullable|exists:stores,id'
        ]);

        $query = Presence::with(['createdBy', 'approvedBy', 'store', 'shiftStore', 'storeOut'])
            ->whereBetween('check_in', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);

        if ($request->has('employee_id')) {
            $query->where('created_by_id', $request->employee_id);
        }

        if ($request->has('store_id')) {
            $query->where('store_id', $request->store_id);
        }

        $presences = $query->get();

        $report = [
            'total_presences' => $presences->count(),
            'total_late_hours' => $presences->sum(function ($presence) {
                return $presence->calculateLateHours();
            }),
            'total_early_checkouts' => $presences->filter(function ($presence) {
                return $presence->getCheckOutStatus() === 'Cepat Pulang';
            })->count(),
            'total_penalty_hours' => $presences->sum(function ($presence) {
                return $presence->calculateTotalPenalty();
            }),
            'total_effective_hours' => $presences->sum(function ($presence) {
                return $presence->calculateEffectiveWorkingTime();
            }),
            'total_salary' => $presences->sum(function ($presence) {
                return $presence->calculateDailySalary();
            }),
            'presences' => $presences
        ];

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }
}