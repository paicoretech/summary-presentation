<?php

namespace App\Http\Controllers;

use App\Services\DownloadJobService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    private DownloadJobService $jobService;

    public function __construct(DownloadJobService $jobService)
    {
        $this->jobService = $jobService;
    }

    public function index()
    {
        return view('dashboard.download');
    }


    public function fetchJobs(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'startDate', 'endDate', 'page', 'size']);
        $jobs = $this->jobService->listJobs($filters);

        if (empty($jobs)) {
            return response()->json(['error' => 'No jobs found or backend error'], 400);
        }

        return response()->json($jobs);
    }

    public function cancel(string $jobId): JsonResponse
    {
        $this->jobService->cancelJob($jobId);
        return response()->json(['status' => 'cancelled']);
    }
    public function clearJob(string $jobId): JsonResponse
    {
        $this->jobService->clearJob($jobId);
        return response()->json(['status' => 'cleared']);
    }
}
