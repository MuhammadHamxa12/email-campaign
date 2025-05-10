<?php

use Illuminate\Support\Facades\Route;
use EmailCampaign\Manager\Controllers\Api\EmailCampaignController;

Route::prefix('api')->middleware('auth:sanctum')->group(function () {
    Route::post('/campaigns', [EmailCampaignController::class, 'create']);
    Route::post('/campaigns/{id}/send', [EmailCampaignController::class, 'send']);
});
