<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\CampaignWebController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/campaigns', [CampaignWebController::class, 'index'])->name('campaigns.index');
    Route::post('/campaigns', [CampaignWebController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/filter', [CampaignWebController::class, 'filter'])->name('campaigns.filter');
    Route::post('/campaigns/send-emails', [CampaignWebController::class, 'sendEmails'])->name('campaigns.sendEmails');
});

