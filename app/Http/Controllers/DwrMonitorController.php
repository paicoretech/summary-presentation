<?php

namespace App\Http\Controllers;

use App\Services\DwrMonitorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DwrMonitorController extends Controller
{
    private DwrMonitorService $dwrService;

    public function __construct(DwrMonitorService $dwrService)
    {
        $this->dwrService = $dwrService;
    }

    public function index()
    {
        $startDate = now()->startOfMonth()->format('Y-m-d H:i:s');
        $endDate   = now()->format('Y-m-d H:i:s');

        return view('dashboard.dwr-monitor', compact('startDate', 'endDate'));
    }

    public function fetch(Request $request): JsonResponse
    {
        $startDate = $request->input('startDate');
        $endDate   = $request->input('endDate');
        $timezone  = $request->input('timezone', 'UTC');

        if (!$startDate || !$endDate) {
            return response()->json(['error' => 'startDate and endDate are required'], 400);
        }

        $records = $this->dwrService->getUnansweredDwrs($startDate, $endDate, $timezone);

        return response()->json($records);
    }
}