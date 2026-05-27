<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DwrMonitorService
{
    protected ?string $backendUrl;

    public function __construct()
    {
        $this->backendUrl = config('services.ANALYTICS_SERVICE_URL');
    }

    public function getUnansweredDwrs(string $startDate, string $endDate, string $timezone = 'UTC'): array
    {
        $response = Http::asJson()->post("{$this->backendUrl}/dwr/unanswered/", [
            'startDate' => $startDate,
            'endDate'   => $endDate,
            'timezone'  => $timezone,
        ]);

        return $response->successful() ? ($response->json() ?? []) : [];
    }
}