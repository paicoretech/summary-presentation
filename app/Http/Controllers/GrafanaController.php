<?php

namespace App\Http\Controllers;
use Log;

use App\Services\GrafanaService;
use Illuminate\Http\Request;

class GrafanaController extends Controller
{
    private $grafanaService;

    public function __construct(GrafanaService $grafanaService)
    {
        $this->grafanaService = $grafanaService;
    }

    /**
     * Obtiene los dashboards de Grafana.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDashboards()
    {
        try {
            $dashboards = $this->grafanaService->get('/api/search', ['type' => 'dash-db']);
            return response()->json(['dashboards' => $dashboards]);
        } catch (\Exception $e) {
            Log::error("error ".$e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }






    public function getAllDashboards()
    {
        $response = $this->grafanaService->get("/api/search", ['type' => 'dash-db']);

        return $response->json();
    }

    public function getPublicDashboards()
    {
        $response = $this->grafanaService->get('/api/dashboards/public-dashboards');

        return $response->json()['publicDashboards'] ?? [];
    }

 /**
     * Get all public dashboards and nested folders.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPublicDashboardsView()
    {
        try {
            // Fetch public dashboards and their access tokens
            $publicDashboardsResponse = $this->grafanaService->get('/api/dashboards/public-dashboards');
            $publicDashboards = $publicDashboardsResponse['publicDashboards'] ?? [];

            // Map dashboard UIDs to access tokens
            $publicDashboardTokens = $this->mapPublicDashboards($publicDashboards);

            // Build the folder and dashboard tree
            $treeData = $this->buildFolderTree(null,null, $publicDashboardTokens);

            // return response()->json(['treeData' => array_values($treeData)]);
            return view('dashboard.charts', [
                'treeData' => array_values($treeData), // Convert associative array to plain array
                'error' => empty($treeData) ? 'No dashboards available.' : null, // Display error if no data
            ]);
        } catch (\Exception $e) {
            // Log the error and redirect back with an error message
            \Log::error("Error fetching Grafana dashboards: {$e->getMessage()}", ['trace' => $e->getTrace()]);
            return redirect()->back()->withErrors('Failed to load dashboards. Please contact support.');
        }
    }

    /**
     * Map public dashboards' UIDs to their access tokens.
     *
     * @param array $publicDashboards
     * @return array
     */
    private function mapPublicDashboards(array $publicDashboards): array
    {
        return collect($publicDashboards)->mapWithKeys(function ($dashboard) {
            return [$dashboard['dashboardUid'] => $dashboard['accessToken']];
        })->toArray();
    }

    /**
     * Recursively build the folder and dashboard tree.
     *
     * @param string|null $parentUid
     * @param array $publicDashboardTokens
     * @return array
     */
    private function buildFolderTree(?int $parentFolderId, ?string $parentUid , array $publicDashboardTokens): array
    {
        $tree = [];

        // Fetch folders for the given parentUid
        $foldersResponse = $this->grafanaService->get('/api/folders', ['parentUid' => $parentUid]);

        foreach ($foldersResponse as $folder) {
            $folderId = $folder['id'];
            $folderUid = $folder['uid'];
            $folderTitle = $folder['title'];

            // Add the folder node
            $tree["folder-$folderId"] = [
                'id' => "folder-$folderId",
                'parent' => $parentFolderId ? "folder-$parentFolderId" : '#',
                'text' => $folderTitle,
                'folderuid'=> $folderUid,
                'type' => 'folder',
            ];

            // Recursively add subfolders and their dashboards
            $subTree = $this->buildFolderTree($folderId, $folderUid, $publicDashboardTokens);
            $tree = array_merge($tree, $subTree);

            // Add dashboards in the current folder
            $dashboardsResponse = $this->grafanaService->get('/api/search', [
                'folderIds' => $folderId,
                'type' => 'dash-db',
            ]);

            foreach ($dashboardsResponse as $dashboard) {
                $isPublic = isset($publicDashboardTokens[$dashboard['uid']]);
                $accessToken = $isPublic ? $publicDashboardTokens[$dashboard['uid']] : null;

                $tree[] = [
                    'id' => "dashboard-{$dashboard['uid']}",
                    'parent' => "folder-$folderId",
                    'text' => $dashboard['title'] . ($isPublic ? ' (Public)' : ''),
                    'type' => 'dashboard',
                    'data' => [
                        'url' => $isPublic ? rtrim(config('services.grafana.api_url'), '/') . "/public-dashboards/{$accessToken}" : null,
                    ],
                ];
            }
        }

        return $tree;
    }

    
    public function listDashboards()
    {
        try {

            $allDashboards = $this->grafanaService->get('/api/search', ['type' => 'dash-db']);
            $publicDashboards = $this->grafanaService->get('/api/dashboards/public-dashboards');


            $publicDashboardUids = array_column($publicDashboards['publicDashboards'] ?? [], 'dashboardUid');

            // group dahsboard
            $groupedDashboards = [];

            foreach ($allDashboards as $dashboard) {
                $folderId = $dashboard['folderId'] ?? 'root';
                $folderTitle = $dashboard['folderTitle'] ?? 'Root';

                if (!isset($groupedDashboards[$folderId])) {
                    $groupedDashboards[$folderId] = [
                        'folderId' => $folderId,
                        'folderTitle' => $folderTitle,
                        'dashboards' => [],
                    ];
                }

                $groupedDashboards[$folderId]['dashboards'][] = [
                    'uid' => $dashboard['uid'],
                    'title' => $dashboard['title'],
                    'isPublic' => in_array($dashboard['uid'], $publicDashboardUids),
                ];
            }

            return response()->json(array_values($groupedDashboards));
        } catch (\Exception $e) {
            \Log::error("Error listing Grafana dashboards: {$e->getMessage()}", ['trace' => $e->getTrace()]);
            return response()->json(['error' => 'Failed to load dashboards.'], 500);
        }
    }



    public function listDashboardsForTree()
{
    try {

        $allDashboards = $this->grafanaService->get('/api/search', ['type' => 'dash-db']);
        $publicDashboards = $this->grafanaService->get('/api/dashboards/public-dashboards');


        $publicDashboardUids = array_column($publicDashboards['publicDashboards'] ?? [], 'dashboardUid');


        $treeData = [];

        foreach ($allDashboards as $dashboard) {
            $folderId = $dashboard['folderId'] ?? 'root';
            $folderTitle = $dashboard['folderTitle'] ?? 'Root';


            if (!array_key_exists("folder-$folderId", $treeData)) {
                $treeData["folder-$folderId"] = [
                    'id' => "folder-$folderId",
                    'parent' => '#', // Raíz
                    'text' => $folderTitle,
                    'type' => 'folder',
                ];
            }

            $treeData[] = [
                'id' => "dashboard-{$dashboard['uid']}",
                'parent' => "folder-$folderId",
                'text' => $dashboard['title'] . ($dashboard['isPublic'] ? ' (Public)' : ''),
                'type' => 'dashboard',
                'data' => [
                    'url' => "{$this->grafanaService->getBaseUrl()}/public-dashboards/{$dashboard['uid']}",
                ],
            ];
        }

        return response()->json(array_values($treeData));
    } catch (\Exception $e) {
        \Log::error("Error listing Grafana dashboards: {$e->getMessage()}", ['trace' => $e->getTrace()]);
        return response()->json(['error' => 'Failed to load dashboards.'], 500);
    }
}





    /**
     * Get all grafana folders.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFolders()
    {
        try {
            $dashboards = $this->grafanaService->get('/api/folders');
            return response()->json(['dashboards' => $dashboards]);
        } catch (\Exception $e) {
            Log::error("error ".$e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Crea un nuevo dashboard en Grafana.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createDashboard(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'panels' => 'required|array',
        ]);

        try {
            $payload = [
                'dashboard' => [
                    'title' => $validated['title'],
                    'panels' => $validated['panels'],
                ],
                'overwrite' => true,
            ];

            $dashboard = $this->grafanaService->post('/api/dashboards/db', $payload);
            return response()->json(['dashboard' => $dashboard]);
        } catch (\Exception $e) {
            Log::error("error ".$e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }
}
