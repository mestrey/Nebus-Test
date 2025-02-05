<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Organization;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Get all organizations in a specific building.
     *
     * @param int $buildingId
     * @return JsonResponse
     */
    public function getOrganizationsByBuilding(int $buildingId): JsonResponse
    {
        $building = Building::find($buildingId);

        if (!$building) {
            return response()->json([
                'status' => 'error',
                'message' => 'Building not found.',
            ], 404);
        }

        $organizations = $building->organizations;

        return response()->json([
            'status' => 'success',
            'data' => $organizations,
        ], 200);
    }

    /**
     * Get all organizations related to a specific activity.
     *
     * @param int $activityId
     * @return JsonResponse
     */
    public function getOrganizationsByActivity(int $activityId): JsonResponse
    {
        $activity = Activity::with('children')->find($activityId);

        if (!$activity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Activity not found.',
            ], 404);
        }

        $organizationIds = $activity->children->pluck('id')->toArray();
        $organizationIds[] = $activityId;

        $organizations = Organization::whereIn('id', function ($query) use ($organizationIds) {
            $query->select('organization_id')
                ->from('activity_organization')
                ->whereIn('activity_id', $organizationIds);
        })->get();

        return response()->json([
            'status' => 'success',
            'data' => $organizations,
        ], 200);
    }

    /**
     * Get organizations within a given radius or rectangular area.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getNearbyOrganizations(Request $request): JsonResponse
    {
        $latitude = (float) $request->input('latitude');
        $longitude = (float) $request->input('longitude');
        $radius = (float) $request->input('radius', 10);

        if (empty($latitude) || empty($longitude)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Latitude and longitude are required.',
            ], 400);
        }

        $distanceQuery = '(6371 * acos(cos(radians(:latitude)) * cos(radians(buildings.latitude)) * cos(radians(buildings.longitude) - radians(:longitude)) + sin(radians(:latitude)) * sin(radians(buildings.latitude))))';

        $organizations = Organization::join('buildings', 'organizations.building_id', '=', 'buildings.id')
            ->select('organizations.*')
            ->whereRaw("$distanceQuery <= :radius", [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'radius' => $radius,
            ])
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $organizations,
        ], 200);
    }

    /**
     * Get organization details by ID.
     *
     * @param int $organizationId
     * @return JsonResponse
     */
    public function show(int $organizationId): JsonResponse
    {
        $organization = Organization::with('building', 'activities')->find($organizationId);

        if (!$organization) {
            return response()->json([
                'status' => 'error',
                'message' => 'Organization not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $organization,
        ], 200);
    }

    /**
     * Search organizations by activity (including child activities).
     *
     * @param int $activityId
     * @return JsonResponse
     */
    public function searchOrganizationsByActivity(int $activityId): JsonResponse
    {
        $activity = Activity::with('children')->find($activityId);

        if (!$activity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Activity not found.',
            ], 404);
        }

        $activityIds = [$activityId];
        $this->collectDescendantActivityIds($activity, $activityIds);

        $organizations = Organization::whereIn('id', function ($query) use ($activityIds) {
            $query->select('organization_id')
                ->from('activity_organization')
                ->whereIn('activity_id', $activityIds);
        })->get();

        return response()->json([
            'status' => 'success',
            'data' => $organizations,
        ], 200);
    }

    /**
     * Search organizations by name.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchByName(Request $request): JsonResponse
    {
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Search query is required.',
            ], 400);
        }

        $organizations = Organization::where('name', 'like', $query . '%')->get();

        return response()->json([
            'status' => 'success',
            'data' => $organizations,
        ], 200);
    }

    /**
     * Recursively collect all descendant activity IDs.
     *
     * @param Activity $activity
     * @param array &$activityIds
     * @return void
     */
    private function collectDescendantActivityIds(Activity $activity, array &$activityIds): void
    {
        foreach ($activity->children as $child) {
            $activityIds[] = $child->id;
            $this->collectDescendantActivityIds($child, $activityIds);
        }
    }
}
