<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class DownloadJobService
{
    protected string $backendUrl;

    public function __construct()
    {
        $this->backendUrl = config('services.ANALYTICS_SERVICE_URL');
    }


    public function listJobs(array $filters = []): array
    {
        $response = Http::get(config('services.ANALYTICS_SERVICE_URL') . '/v2/list', $filters);
        return $response->successful() ? $response->json() : [];
    }


    public function cancelJob(string $jobId): bool
    {
        $response = Http::post("{$this->backendUrl}/v2/{$jobId}/cancel");
        return $response->successful();
    }

    public function downloadUrl(string $jobId): string
    {
        return "{$this->backendUrl}/v2/download/{$jobId}";
    }
}
