<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MockBiodataController;

// Mock Biodata API for development
Route::get('/mock/biodata/{nip}', [MockBiodataController::class, 'show']);
