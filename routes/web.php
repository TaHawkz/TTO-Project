<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\IndustryInquiryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Portal\Admin\StartupController as AdminStartupController;
use App\Http\Controllers\Portal\Admin\TechnologyController as AdminTechnologyController;
use App\Http\Controllers\Portal\Admin\UserController as AdminUserController;
use App\Http\Controllers\Portal\AgreementController;
use App\Http\Controllers\Portal\AssignmentController;
use App\Http\Controllers\Portal\CommercializationController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\DisclosureController;
use App\Http\Controllers\Portal\DocumentController;
use App\Http\Controllers\Portal\PatentController;
use App\Http\Controllers\Portal\PatentDeadlineController;
use App\Http\Controllers\Portal\ReportController;
use App\Http\Controllers\Portal\RevenueController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TechnologyController;
use Illuminate\Support\Facades\Route;

// ─── Phase 1: Public-facing website ──────────────────────────────────────────
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/ip-policy', [PageController::class, 'ipPolicy'])->name('ip-policy');
Route::get('/sop-guidelines', [PageController::class, 'sopGuidelines'])->name('sop-guidelines');
Route::get('/forms-templates', [PageController::class, 'formsTemplates'])->name('forms-templates');
Route::get('/disclosure', [PageController::class, 'disclosure'])->name('disclosure');
Route::get('/technology-portfolio', [TechnologyController::class, 'index'])->name('technology-portfolio');
Route::get('/industry-collaboration', [PageController::class, 'industryCollaboration'])->name('industry-collaboration');
Route::get('/industry-inquiry', [IndustryInquiryController::class, 'create'])->name('industry-inquiry');
Route::post('/industry-inquiry', [IndustryInquiryController::class, 'store'])->name('industry-inquiry.store');
Route::get('/startups', [PageController::class, 'startups'])->name('startups');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');

// ─── Breeze required routes ──────────────────────────────────────────────────
Route::get('/dashboard', function () {
    return redirect()->route('portal.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ─── Breeze profile routes ────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Portal ───────────────────────────────────────────────────────────────────
Route::prefix('portal')->name('portal.')->middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Disclosures
    Route::resource('disclosures', DisclosureController::class)->except(['destroy']);
    Route::post('disclosures/{disclosure}/submit', [DisclosureController::class, 'submit'])
        ->name('disclosures.submit');
    Route::post('disclosures/{disclosure}/status', [DisclosureController::class, 'updateStatus'])
        ->middleware('role:reviewer,tto_officer,legal_officer,director,system_admin')
        ->name('disclosures.status');
    Route::post('disclosures/{disclosure}/assign', [DisclosureController::class, 'assign'])
        ->middleware('role:tto_officer,director,system_admin')
        ->name('disclosures.assign');

    // Document download
    Route::get('documents/{document}/download', [DocumentController::class, 'download'])
        ->name('documents.download');

    // Patents
    Route::resource('patents', PatentController::class)->except(['destroy'])
        ->middleware('role:tto_officer,legal_officer,director,system_admin');
    Route::middleware('role:tto_officer,legal_officer,director,system_admin')
        ->scopeBindings()
        ->group(function () {
            Route::resource('patents.deadlines', PatentDeadlineController::class)
                ->only(['store', 'update', 'destroy']);
        });

    // IP Assignments
    Route::resource('assignments', AssignmentController::class)
        ->only(['index', 'show', 'create', 'store'])
        ->middleware('role:tto_officer,legal_officer,director,system_admin');

    // Agreements
    Route::resource('agreements', AgreementController::class)->except(['destroy'])
        ->middleware('role:tto_officer,legal_officer,director,system_admin');
    Route::get('agreements/{agreement}/download', [AgreementController::class, 'download'])
        ->middleware('role:tto_officer,legal_officer,director,system_admin')
        ->name('agreements.download');

    // Commercialization
    Route::resource('commercialization', CommercializationController::class)->except(['destroy'])
        ->middleware('role:tto_officer,director,system_admin');

    // Revenue
    Route::resource('revenue', RevenueController::class)->only(['index', 'show', 'create', 'store'])
        ->middleware('role:tto_officer,director,system_admin');
    Route::post('revenue/{revenue}/distributions/{distribution}/mark-paid', [RevenueController::class, 'markPaid'])
        ->middleware('role:tto_officer,director,system_admin')
        ->name('revenue.distributions.mark-paid');

    // Reports
    Route::prefix('reports')->name('reports.')->middleware('role:tto_officer,director,system_admin')->group(function () {
        Route::get('/',                  [ReportController::class, 'index'])->name('index');
        Route::get('/disclosures',       [ReportController::class, 'disclosures'])->name('disclosures');
        Route::get('/patents',           [ReportController::class, 'patents'])->name('patents');
        Route::get('/revenue',           [ReportController::class, 'revenue'])->name('revenue');
        Route::get('/commercialization', [ReportController::class, 'commercialization'])->name('commercialization');
        Route::get('/export/{type}',     [ReportController::class, 'export'])->name('export');
    });

    // Admin — user management (system_admin only)
    Route::prefix('admin')->name('admin.')->middleware('role:system_admin')->group(function () {
        Route::resource('users', AdminUserController::class)->only(['index', 'edit', 'update']);
    });

    // Admin — technology & startup content (tto_officer or system_admin)
    Route::prefix('admin')->name('admin.')->middleware('role:tto_officer,system_admin')->group(function () {
        Route::resource('technologies', AdminTechnologyController::class)->except(['show']);
        Route::resource('startups', AdminStartupController::class)->except(['show']);
    });
});

require __DIR__.'/auth.php';
