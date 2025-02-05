<?php

use App\Http\Middleware\AuthenticateWithToken;
use Illuminate\Support\Facades\Route;

Route::middleware(AuthenticateWithToken::class)->group(function () {
    // List all organizations in a specific building
    Route::get('/buildings/{buildingId}/organizations', [App\Http\Controllers\OrganizationController::class, 'getOrganizationsByBuilding'])
        ->name('organizations.by_building');

    // List all organizations related to a specific activity
    Route::get('/activities/{activityId}/organizations', [App\Http\Controllers\OrganizationController::class, 'getOrganizationsByActivity'])
        ->name('organizations.by_activity');

    // List organizations within a given radius or rectangular area
    Route::get('/organizations/nearby', [App\Http\Controllers\OrganizationController::class, 'getNearbyOrganizations'])
        ->name('organizations.nearby');

    // List all buildings
    Route::get('/buildings', [App\Http\Controllers\BuildingController::class, 'index'])
        ->name('buildings.index');

    // Search organizations by name
    Route::get('/organizations/search', [App\Http\Controllers\OrganizationController::class, 'searchByName'])
        ->name('organizations.search_by_name');

    // Get organization details by ID
    Route::get('/organizations/{organizationId}', [App\Http\Controllers\OrganizationController::class, 'show'])
        ->name('organizations.show');

    // Search organizations by activity (including child activities)
    Route::get('/activities/{activityId}/search-organizations', [App\Http\Controllers\OrganizationController::class, 'searchOrganizationsByActivity'])
        ->name('organizations.search_by_activity');
});
