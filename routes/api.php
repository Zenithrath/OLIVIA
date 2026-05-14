<?php

use App\Http\Controllers\Api\ContractAnalysisController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:30,1')->group(function (): void {
    Route::get('/ai-engine/health', [ContractAnalysisController::class, 'engineHealth']);
    Route::get('/contracts/{contract}', [ContractAnalysisController::class, 'show']);
    Route::post('/contracts/analyze', [ContractAnalysisController::class, 'analyze']);
});
