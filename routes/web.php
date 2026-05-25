<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\CenterOfExcellenceController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\InsurancePartnerController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\PromoController as AdminPromoController;
use App\Http\Controllers\Admin\CenterOfExcellenceController as AdminCoeController;
use App\Http\Controllers\Admin\FacilityController as AdminFacilityController;
use App\Http\Controllers\Admin\SpecialtyController as AdminSpecialtyController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\InsurancePartnerController as AdminInsurancePartnerController;
use App\Http\Controllers\Admin\JobVacancyController as AdminJobVacancyController;
use App\Http\Controllers\PackageController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dokter', [DoctorController::class, 'index'])->name('doctors.index');
Route::get('/dokter/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');

Route::get('/layanan-unggulan', [CenterOfExcellenceController::class, 'index'])->name('coes.index');
Route::get('/layanan-unggulan/{centerOfExcellence:slug}', [CenterOfExcellenceController::class, 'show'])->name('coes.show');

Route::get('/fasilitas', [FacilityController::class, 'index'])->name('facilities.index');

Route::get('/halaman/{page:slug}', [PageController::class, 'show'])->name('pages.show');

Route::get('/artikel', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/artikel/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');

Route::get('/paket-kesehatan', [PackageController::class, 'index'])->name('packages.index');
Route::get('/paket-kesehatan/{package:slug}', [PackageController::class, 'show'])->name('packages.show');

Route::get('/mitra-asuransi', [InsurancePartnerController::class, 'index'])->name('insurance-partners.index');
Route::get('/karir', [JobVacancyController::class, 'index'])->name('careers.index');

// Admin Routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('doctors', AdminDoctorController::class);
    Route::resource('articles', AdminArticleController::class);
    Route::resource('promos', AdminPromoController::class);
    Route::resource('article-categories', \App\Http\Controllers\Admin\ArticleCategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('facility-categories', \App\Http\Controllers\Admin\FacilityCategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('coes', AdminCoeController::class)->parameters(['coes' => 'coe']);
    Route::resource('facilities', AdminFacilityController::class);
    Route::resource('specialties', AdminSpecialtyController::class)->except(['create', 'show', 'edit']);
    Route::resource('degrees', \App\Http\Controllers\Admin\DegreeController::class)->except(['create', 'show', 'edit']);
    Route::resource('pages', AdminPageController::class);
    Route::resource('insurance-partners', AdminInsurancePartnerController::class)->parameters(['insurance-partners' => 'insurancePartner']);
    Route::resource('job-vacancies', AdminJobVacancyController::class)->parameters(['job-vacancies' => 'jobVacancy']);
    
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});

// Redirect old dashboard route to admin.dashboard to prevent Breeze errors
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
