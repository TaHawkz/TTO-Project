<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\IndustryInquiryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TechnologyController;
use Illuminate\Support\Facades\Route;

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
Route::redirect('/portal', '/');
