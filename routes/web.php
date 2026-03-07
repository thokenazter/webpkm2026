<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pkm\PkmHomeController;
use App\Http\Controllers\Pkm\BeritaController;
use App\Http\Controllers\Pkm\GaleriController;
use App\Http\Controllers\Pkm\ChatbotController;
use App\Http\Controllers\Pkm\ContactController;
use App\Http\Controllers\Pkm\TimLayananController;
use App\Http\Controllers\Pkm\Admin\PkmDashboardController;
use App\Http\Controllers\Pkm\Admin\PostController;
use App\Http\Controllers\Pkm\Admin\GalleryController;
use App\Http\Controllers\Pkm\Admin\CategoryController;
use App\Http\Controllers\Pkm\Admin\MessageController;

/*
|--------------------------------------------------------------------------
| PKM Public Routes (Website Puskesmas)
|--------------------------------------------------------------------------
| These routes serve the public Puskesmas website as the root landing page.
*/

// Homepage (root URL)
Route::get('/', [PkmHomeController::class, 'index'])->name('pkm.home');

// Berita
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{post:slug}', [BeritaController::class, 'show'])->name('berita.show');
Route::get('/api/berita/search', [BeritaController::class, 'search'])->name('berita.search');

// Galeri
Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri.index');

// Static pages (klaster, profil, etc.)
Route::get('/klaster-1', fn() => view('pkm.klaster.klaster1'))->name('pkm.klaster1');
Route::get('/klaster-2', fn() => view('pkm.klaster.klaster2'))->name('pkm.klaster2');
Route::get('/klaster-3', fn() => view('pkm.klaster.klaster3'))->name('pkm.klaster3');
Route::get('/klaster-4', fn() => view('pkm.klaster.klaster4'))->name('pkm.klaster4');
Route::get('/lintas-klaster', fn() => view('pkm.klaster.lintas'))->name('pkm.lintas');
Route::get('/struktur-organisasi', fn() => view('pkm.profil.struktur-organisasi'))->name('pkm.struktur');
Route::get('/tim-layanan', [TimLayananController::class, 'index'])->name('pkm.tim-layanan');

// Hubungi Kami
Route::get('/hubungi-kami', fn() => view('pkm.hubungi-kami'))->name('pkm.hubungi-kami');
Route::post('/hubungi-kami', [ContactController::class, 'store'])->name('pkm.contact.store');

// Chatbot API
Route::post('/api/chatbot', [ChatbotController::class, 'chat'])->name('pkm.chatbot');

/*
|--------------------------------------------------------------------------
| Post-Login Choice Page
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', config('jetstream.auth_session')])->group(function () {
    Route::get('/pilih-layanan', fn() => view('post-login'))->name('pilih-layanan');
});

/*
|--------------------------------------------------------------------------
| PKM Admin CMS Routes
|--------------------------------------------------------------------------
| Accessible to users with canManageContent() permission (admin/super_admin/editor).
*/
Route::prefix('pkm-admin')
    ->middleware(['auth:sanctum', config('jetstream.auth_session'), \App\Http\Middleware\PkmAdminMiddleware::class])
    ->name('pkm-admin.')
    ->group(function () {
        Route::get('/', [PkmDashboardController::class, 'index'])->name('dashboard');
        Route::resource('posts', PostController::class)->except(['show']);
        Route::resource('galleries', GalleryController::class)->except(['show']);
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
        Route::get('messages/{message}', [MessageController::class, 'show'])->name('messages.show');
        Route::delete('messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    });

/*
|--------------------------------------------------------------------------
| BOK Application Routes (existing)
|--------------------------------------------------------------------------
*/

// Route untuk halaman pending approval
Route::get('/approval/pending', fn() => view('auth.approval-pending'))->name('approval.pending');

// API Route untuk pengumuman aktif (public)
Route::get('/api/pengumuman/active', [App\Http\Controllers\PengumumanController::class, 'getActive'])->name('api.pengumuman.active');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'approved',
])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Master Data Routes
    Route::resource('employees', App\Http\Controllers\EmployeeController::class);
    Route::resource('activities', App\Http\Controllers\ActivityController::class);
    Route::get('activities/{activity}/budget', [App\Http\Controllers\ActivityController::class, 'budget'])->name('activities.budget');
    Route::put('activities/{activity}/budget', [App\Http\Controllers\ActivityController::class, 'updateBudget'])->name('activities.update_budget');
    // Per-diem-rates tidak diperlukan karena uang harian fixed Rp 150.000 per desa
    // Route::resource('per-diem-rates', App\Http\Controllers\PerDiemRateController::class);

    // Master RAB (admin-only moved below)

    // LPJ Routes
    Route::resource('lpjs', App\Http\Controllers\LpjController::class);
    Route::post('lpjs/auto-schedule', [App\Http\Controllers\LpjController::class, 'autoSchedule'])->name('lpjs.auto_schedule');
    Route::post('lpjs/bulk-delete', [App\Http\Controllers\LpjController::class, 'bulkDelete'])->name('lpjs.bulk-delete');
    Route::get('lpjs/search/employees', [App\Http\Controllers\LpjController::class, 'searchEmployees'])->name('lpjs.search.employees');
    Route::get('lpjs/search/activities', [App\Http\Controllers\LpjController::class, 'searchActivities'])->name('lpjs.search.activities');
    Route::post('lpjs/create-activity', [App\Http\Controllers\LpjController::class, 'createActivity'])->name('lpjs.create.activity');
    // Create LPJ from existing (e.g., continue SPPT -> SPPD)
    Route::get('lpjs/{lpj}/create-from', [App\Http\Controllers\LpjController::class, 'createFrom'])->name('lpjs.create_from');

    // Employee Saldo Routes
    Route::get('/employee-saldo', [App\Http\Controllers\EmployeeSaldoController::class, 'index'])->name('employee-saldo.index');
    Route::get('/employee-saldo/{employee}', [App\Http\Controllers\EmployeeSaldoController::class, 'show'])->name('employee-saldo.show');

    // LPJ Document Routes
    Route::get('/lpj/{lpj}/download', [App\Http\Controllers\LpjDocumentController::class, 'download'])->name('lpj.download');
    Route::get('/lpj/{lpj}/preview', [App\Http\Controllers\LpjDocumentController::class, 'preview'])->name('lpj.preview');
    Route::post('/lpj/{lpj}/regenerate', [App\Http\Controllers\LpjDocumentController::class, 'regenerate'])->name('lpj.regenerate');
    Route::get('/lpj/download-multiple', [App\Http\Controllers\LpjDocumentController::class, 'downloadMultiple'])->name('lpj.download_multiple');

    // Pejabat TTD Routes (read-only for regular users)
    Route::resource('pejabat-ttd', App\Http\Controllers\PejabatTtdController::class)->only(['index', 'show']);

    // Tiba Berangkat Routes
    Route::resource('tiba-berangkats', App\Http\Controllers\TibaBerangkatController::class);
    Route::post('tiba-berangkats/bulk-delete', [App\Http\Controllers\TibaBerangkatController::class, 'bulkDelete'])->name('tiba-berangkats.bulk-delete');
    Route::get('/tiba-berangkats/{tibaBerangkat}/download', [App\Http\Controllers\TibaBerangkatController::class, 'download'])->name('tiba-berangkats.download');
    Route::post('/tiba-berangkats/{tibaBerangkat}/quick-update', [App\Http\Controllers\TibaBerangkatController::class, 'quickUpdate'])->name('tiba-berangkats.quick_update');
    Route::get('/api/pejabat-by-desa', [App\Http\Controllers\TibaBerangkatController::class, 'getPejabatByDesa'])->name('api.pejabat-by-desa');
    // Auto-create TB from a single LPJ (SPPT or SPPD)
    Route::get('/tiba-berangkats/auto-from-lpj/{lpj}', [App\Http\Controllers\TibaBerangkatController::class, 'autoFromLpj'])->name('tiba-berangkats.auto_from_lpj');

    // Item Opsional (Kwitansi) Download
    Route::get('/item-opsional/{itemOpsionalClaim}/download', [App\Http\Controllers\ItemOpsionalController::class, 'download'])->name('item-opsional.download');

    // RAB Routes (read-only for regular users)
    Route::resource('rabs', App\Http\Controllers\RabController::class)->only(['index']);
    Route::get('/api/rabs/info-by-kegiatan', [App\Http\Controllers\RabController::class, 'infoByKegiatan'])->name('rabs.info_by_kegiatan');
    Route::get('/api/rabs/{rab}/basic', [App\Http\Controllers\RabController::class, 'basic'])->name('rabs.basic');
    Route::get('/api/rabs/{rab}/targets', [App\Http\Controllers\RabController::class, 'targets'])->name('rabs.targets');

    // Budgets & Allocations
    // Allocations: read-only for regular users
    Route::resource('allocations', App\Http\Controllers\BudgetAllocationController::class)->only(['index']);
    Route::get('/api/allocations/summary-by-kegiatan', [App\Http\Controllers\BudgetAllocationController::class, 'summaryByKegiatan'])->name('allocations.summary_by_kegiatan');

    // POA Routes
    Route::get('/kalender-kegiatan', [App\Http\Controllers\PoaController::class, 'calendar'])->name('poa.calendar');
    Route::get('/poa/{poa}/calendar-detail/{month}', [App\Http\Controllers\PoaController::class, 'calendarDetail'])->name('poa.calendar_detail');
    Route::get('/poa/{poa}/calendar-claim-prep/{month}', [App\Http\Controllers\PoaController::class, 'calendarClaimPrep'])->name('poa.calendar_claim_prep');
    Route::resource('poa', App\Http\Controllers\PoaController::class);
    Route::get('/api/poa/available-rabs', [App\Http\Controllers\PoaController::class, 'availableRabs'])->name('poa.available_rabs');
    Route::post('/poa/{poa}/schedule/carryover', [App\Http\Controllers\PoaController::class, 'carryOver'])->name('poa.schedule.carryover');
    Route::post('/poa/{poa}/schedule/toggle-mark', [App\Http\Controllers\PoaController::class, 'toggleMark'])->name('poa.schedule.toggle_mark');
    Route::post('/poa/{poa}/schedule/upsert-month', [App\Http\Controllers\PoaController::class, 'upsertMonthMeta'])->name('poa.schedule.upsert_month');
    Route::post('/poa/{poa}/schedule/toggle-claim-label', [App\Http\Controllers\PoaController::class, 'toggleClaimLabel'])->name('poa.schedule.toggle_claim_label');
    Route::post('/poa/{poa}/schedule/toggle-claim-lock', [App\Http\Controllers\PoaController::class, 'toggleClaimLock'])->name('poa.schedule.toggle_claim_lock');
    Route::post('/poa/{poa}/item-progress', [App\Http\Controllers\PoaController::class, 'updateItemProgress'])->name('poa.item_progress.update');
    Route::post('/poa/{poa}/claim', [App\Http\Controllers\PoaController::class, 'claim'])->name('poa.claim');

    // Pengumuman Routes moved to super admin group below
});

// Admin Routes - Super Admin Only
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'approved', 'super_admin'])->group(function () {
    Route::resource('/users', App\Http\Controllers\Admin\UserController::class);
    Route::post('/users/{user}/approve', [App\Http\Controllers\Admin\UserController::class, 'approve'])->name('users.approve');
    // Ledger (BKU) page on main dashboard (super admin only)
    Route::get('/bok/ledger', [App\Http\Controllers\LedgerPageController::class, 'index'])->name('bok.ledger.page');
    // Template Manager
    Route::get('/bok/templates', [\App\Modules\BOK\Http\Controllers\TemplateController::class, 'index'])->name('bok.templates.index');
    Route::get('/bok/templates/create', [\App\Modules\BOK\Http\Controllers\TemplateController::class, 'create'])->name('bok.templates.create');
    Route::post('/bok/templates', [\App\Modules\BOK\Http\Controllers\TemplateController::class, 'store'])->name('bok.templates.store');
    Route::post('/bok/templates/{template}/activate', [\App\Modules\BOK\Http\Controllers\TemplateController::class, 'activate'])->name('bok.templates.activate');
    Route::delete('/bok/templates/{template}', [\App\Modules\BOK\Http\Controllers\TemplateController::class, 'destroy'])->name('bok.templates.destroy');
    Route::get('/bok/templates/{template}/download', [\App\Modules\BOK\Http\Controllers\TemplateController::class, 'download'])->name('bok.templates.download');
    // Bank Reconciliation
    Route::get('/bok/reconciliations', [\App\Modules\BOK\Http\Controllers\BankReconciliationController::class, 'index'])->name('bok.recon.index');
    Route::get('/bok/reconciliations/create', [\App\Modules\BOK\Http\Controllers\BankReconciliationController::class, 'create'])->name('bok.recon.create');
    Route::post('/bok/reconciliations', [\App\Modules\BOK\Http\Controllers\BankReconciliationController::class, 'store'])->name('bok.recon.store');
    Route::get('/bok/reconciliations/{reconciliation}', [\App\Modules\BOK\Http\Controllers\BankReconciliationController::class, 'show'])->name('bok.recon.show');

    // RAB management (create/update/delete/export) - Super Admin only
    Route::resource('rabs', App\Http\Controllers\RabController::class)->except(['index','show']);
    Route::get('/rabs/{rab}/export', [App\Http\Controllers\RabController::class, 'export'])->name('rabs.export');
    Route::get('/rabs/export-master', [App\Http\Controllers\RabController::class, 'exportMaster'])->name('rabs.export-master');
    Route::get('/rabs/export-master-template', [App\Http\Controllers\RabController::class, 'exportMasterTemplate'])->name('rabs.export-master-template');
    Route::get('/rabs/export-all-templated', [App\Http\Controllers\RabController::class, 'exportAllTemplated'])->name('rabs.export-all-templated');
    Route::get('/rabs/export-puskesmas-template', [App\Http\Controllers\RabController::class, 'exportPuskesmasTemplate'])->name('rabs.export-puskesmas-template');
    Route::get('/rabs/generate-all-template', [App\Http\Controllers\RabController::class, 'generateAllTemplate'])->name('rabs.generate-all-template');
    Route::get('/rabs/export-multiple-stacked', [App\Http\Controllers\RabController::class, 'exportMultipleStacked'])->name('rabs.export-multiple-stacked');
    Route::get('/rabs/diagnose-stacked-template', [App\Http\Controllers\RabController::class, 'diagnoseStackedTemplate'])->name('rabs.diagnose-stacked-template');

    // Allocations management (create/update/delete) - Super Admin only
    Route::resource('allocations', App\Http\Controllers\BudgetAllocationController::class)->except(['index','show']);
    Route::get('/api/allocations/available-rabs', [App\Http\Controllers\BudgetAllocationController::class, 'availableRabs'])->name('allocations.available_rabs');

    // POA bulk actions
    Route::post('/poa/bulk-toggle-claim-lock', [App\Http\Controllers\PoaController::class, 'bulkToggleClaimLock'])->name('poa.bulk_toggle_claim_lock');
});

// Admin Routes - Admin or Super Admin
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'approved', 'admin'])->group(function () {
    // Restrict these master data to admin/super admin
    Route::resource('villages', App\Http\Controllers\VillageController::class);
    Route::resource('rate-settings', App\Http\Controllers\RateSettingController::class);

    // Pejabat TTD management (create/edit/delete - admin only)
    Route::resource('pejabat-ttd', App\Http\Controllers\PejabatTtdController::class)->except(['index', 'show']);

    // Master RAB (admin/super admin)
    Route::get('rab-menus/by-component', [App\Http\Controllers\RabMenuController::class, 'byComponent'])->name('rab-menus.by-component');
    Route::resource('rab-menus', App\Http\Controllers\RabMenuController::class);
    Route::get('rab-kegiatans/by-menu', [App\Http\Controllers\RabKegiatanController::class, 'byMenu'])->name('rab-kegiatans.by-menu');
    Route::resource('rab-kegiatans', App\Http\Controllers\RabKegiatanController::class);

    // Budgets (Pagu Tahunan)
    Route::resource('budgets', App\Http\Controllers\AnnualBudgetController::class);

    // Pengumuman (Announcements)
    Route::resource('pengumuman', App\Http\Controllers\PengumumanController::class)->except(['show']);
    Route::patch('pengumuman/{pengumuman}/toggle', [App\Http\Controllers\PengumumanController::class, 'toggle'])->name('pengumuman.toggle');

    // BOK Treasurer: Ledger export & period lock
    Route::get('bok/ledger/export', [\App\Modules\BOK\Http\Controllers\LedgerExportController::class, 'export'])->name('bok.ledger.export');
    Route::get('bok/ledger/export-pdf', [\App\Modules\BOK\Http\Controllers\LedgerExportController::class, 'pdf'])->name('bok.ledger.export-pdf');
    Route::post('bok/ledger/period/toggle', [\App\Modules\BOK\Http\Controllers\LedgerPeriodController::class, 'toggle'])->name('bok.ledger.period.toggle');
    Route::post('bok/ledger/opening', [\App\Modules\BOK\Http\Controllers\LedgerOpeningController::class, 'store'])->name('bok.ledger.opening.store');

    // Activity Schedules (Jadwal Bulanan)
    Route::get('jadwal', [App\Http\Controllers\ActivityScheduleController::class, 'index'])->name('jadwal.index');
    Route::post('jadwal/generate', [App\Http\Controllers\ActivityScheduleController::class, 'generate'])->name('jadwal.generate');
    Route::put('jadwal/{schedule}', [App\Http\Controllers\ActivityScheduleController::class, 'update'])->name('jadwal.update');
    Route::post('jadwal/finalize', [App\Http\Controllers\ActivityScheduleController::class, 'finalize'])->name('jadwal.finalize');
    Route::post('jadwal/reset', [App\Http\Controllers\ActivityScheduleController::class, 'reset'])->name('jadwal.reset');
    Route::post('jadwal/unlock', [App\Http\Controllers\ActivityScheduleController::class, 'unlock'])->name('jadwal.unlock');
});

// Place dynamic {id} show routes after admin CRUD to avoid conflicts with 
// '/create' being captured by '/{id}'
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'approved'])->group(function () {
    Route::get('rabs/{rab}', [App\Http\Controllers\RabController::class, 'show'])->name('rabs.show');
    Route::get('allocations/{allocation}', [App\Http\Controllers\BudgetAllocationController::class, 'show'])->name('allocations.show');
});
