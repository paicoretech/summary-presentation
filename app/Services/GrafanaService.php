<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class GrafanaService
{
    private $apiUrl;
    private $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.grafana.api_url');
        $this->apiKey = config('services.grafana.api_key');

        if (empty($this->apiUrl) || empty($this->apiKey)) {
            throw new \InvalidArgumentException('Grafana API URL or API Key is not configured.');
        }
    }

    /**
     * Makes a GET request to the Grafana API.
     *
     * @param string $endpoint
     * @param array $queryParams
     * @return mixed
     */
    public function get(string $endpoint, array $queryParams = [])
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get($this->buildUrl($endpoint), $queryParams);

        return $this->handleResponse($response);
    }

    /**
     * Makes a POST request to the Grafana API.
     *
     * @param string $endpoint
     * @param array $data
     * @return mixed
     */
    public function post(string $endpoint, array $data)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post($this->buildUrl($endpoint), $data);

        return $this->handleResponse($response);
    }

    /**
     * Makes a PUT request to the Grafana API.
     *
     * @param string $endpoint
     * @param array $data
     * @return mixed
     */
    public function put(string $endpoint, array $data)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->put($this->buildUrl($endpoint), $data);

        return $this->handleResponse($response);
    }

    /**
     * Makes a DELETE request to the Grafana API.
     *
     * @param string $endpoint
     * @return mixed
     */
    public function delete(string $endpoint)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->delete($this->buildUrl($endpoint));

        return $this->handleResponse($response);
    }

    /**
     * Returns the necessary headers for the Grafana API requests.
     *
     * @return array
     */
    private function getHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept' => 'application/json',
        ];
    }

    /**
     * Constructs the full URL for a given API endpoint.
     *
     * @param string $endpoint
     * @return string
     */
    private function buildUrl(string $endpoint)
    {
        return rtrim($this->apiUrl, '/') . '/' . ltrim($endpoint, '/');
    }

    /**
     * Handles the API response and throws errors if necessary.
     *
     * @param Response $response
     * @return mixed
     * @throws \Exception
     */
    private function handleResponse(Response $response)
    {
        if ($response->successful()) {
            return $response->json();
        }

        $status = $response->status();
        $errorBody = $response->body();

        throw new \Exception(
            "Grafana API request failed with status {$status}: {$errorBody}",
            $status
        );
    }
}
