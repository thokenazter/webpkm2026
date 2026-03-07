<?php

use App\Http\Controllers\API\MobileCalendarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ─── Mobile App API Routes ──────────────────────────────────────────────────
Route::prefix('mobile')->group(function () {
    // Auth (no middleware needed for login)
    Route::post('/login', [MobileCalendarController::class, 'login']);

    // Protected routes (require Sanctum token)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/calendar', [MobileCalendarController::class, 'calendar']);
        Route::get('/calendar/{poa}/detail/{month}', [MobileCalendarController::class, 'calendarDetail']);

        // Claim
        Route::get('/calendar/{poa}/claim-prep/{month}', [MobileCalendarController::class, 'claimPrep']);
        Route::post('/calendar/{poa}/claim', [MobileCalendarController::class, 'claim']);

        // Downloads
        Route::get('/lpj/{lpj}/download', [MobileCalendarController::class, 'lpjDownload']);
        Route::get('/tiba-berangkat/{tibaBerangkat}/download', [MobileCalendarController::class, 'tibaBerangkatDownload']);
        Route::get('/item-opsional/{itemOpsionalClaim}/download', [MobileCalendarController::class, 'itemOpsionalDownload']);

        // ─── POA ────────────────────────────────────────────────────────────────
        Route::get('/poa', [MobileCalendarController::class, 'poaList']);
        Route::get('/poa/available-rabs', [MobileCalendarController::class, 'poaAvailableRabs']);
        Route::get('/poa/{poa}', [MobileCalendarController::class, 'poaDetail']);
        Route::post('/poa', [MobileCalendarController::class, 'poaStore']);
        Route::put('/poa/{poa}', [MobileCalendarController::class, 'poaUpdate']);
        Route::delete('/poa/{poa}', [MobileCalendarController::class, 'poaDestroy']);
        Route::post('/poa/{poa}/toggle-mark', [MobileCalendarController::class, 'poaToggleMark']);
        Route::post('/poa/{poa}/toggle-claim-lock', [MobileCalendarController::class, 'poaToggleClaimLock']);
        Route::post('/poa/{poa}/toggle-claim-label', [MobileCalendarController::class, 'poaToggleClaimLabel']);
        Route::post('/poa/{poa}/upsert-month', [MobileCalendarController::class, 'poaUpsertMonth']);
        Route::post('/poa/{poa}/item-progress', [MobileCalendarController::class, 'poaItemProgress']);
        Route::post('/poa/{poa}/carry-over', [MobileCalendarController::class, 'poaCarryOver']);
        Route::post('/poa/bulk-lock', [MobileCalendarController::class, 'poaBulkLock']);
        Route::get('/employees', [MobileCalendarController::class, 'employeeList']);
    });
});
