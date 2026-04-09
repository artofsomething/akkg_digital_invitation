<?php

use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\InvitationController;
use App\Http\Controllers\User\SectionController;
use App\Http\Controllers\User\GalleryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TemplateController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\PublicInvitationController;
use App\Http\Controllers\Public\GuestBookController;
use App\Http\Controllers\Public\RsvpController;

// ========== PUBLIC ==========
Route::get('/', fn() => view('welcome'))->name('home');

// ========== AUTH ==========
require __DIR__ . '/auth.php';

// ✅ Dashboard redirect
Route::middleware(['auth'])->get('/dashboard', function () {
    if (auth()->user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->name('dashboard');

// ========== USER ==========
Route::middleware(['auth', 'user'])->prefix('dashboard')->name('user.')->group(function () {

    Route::get('/', [UserDashboardController::class, 'index'])->name('dashboard');

    // Invitation Steps
    Route::get('/invitation/category', [InvitationController::class, 'selectCategory'])->name('invitation.category');
    Route::get('/invitation/template/{category:slug}', [InvitationController::class, 'selectTemplate'])->name('invitation.template');
    Route::get('/invitation/create/{template:slug}', [InvitationController::class, 'create'])->name('invitation.create');

    // Invitation CRUD
    Route::post('/invitation', [InvitationController::class, 'store'])->name('invitation.store');
    Route::get('/invitation/{invitation:slug}/edit', [InvitationController::class, 'edit'])->name('invitation.edit');
    Route::put('/invitation/{invitation:slug}', [InvitationController::class, 'update'])->name('invitation.update');
    Route::patch('/invitation/{invitation:slug}/publish', [InvitationController::class, 'publish'])->name('invitation.publish');
    Route::patch('/invitation/{invitation:slug}/unpublish', [InvitationController::class, 'unpublish'])->name('invitation.unpublish');
    Route::delete('/invitation/{invitation:slug}', [InvitationController::class, 'destroy'])->name('invitation.destroy');

    // Sections
    Route::put('/invitation/{invitation:slug}/section/{section}', [SectionController::class, 'update'])->name('section.update');
    Route::patch('/invitation/{invitation:slug}/section/{section}/toggle', [SectionController::class, 'toggleVisibility'])->name('section.toggle');
    Route::post('/invitation/{invitation:slug}/sections/reorder', [SectionController::class, 'reorder'])->name('section.reorder');

    // Gallery
    Route::post('/gallery/{invitation:slug}', [GalleryController::class, 'store'])->name('gallery.store');
    Route::patch('/gallery/{gallery}/caption', [GalleryController::class, 'updateCaption'])->name('gallery.caption');
    Route::delete('/gallery/{gallery}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
    Route::post('/gallery/{invitation:slug}/reorder', [GalleryController::class, 'reorder'])->name('gallery.reorder');
});

// ========== ADMIN ==========
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::get('/users/{user}', [AdminDashboardController::class, 'userDetail'])->name('user.detail');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::resource('templates', TemplateController::class);
});

Route::prefix('inv')->name('invitation.')->group(function () {
    Route::get('/{slug}', [PublicInvitationController::class, 'show'])
        ->name('show');
    Route::post('/{slug}/guest-book', [GuestBookController::class, 'store'])
        ->name('guest-book.store');
    Route::get('/{slug}/guest-book', [GuestBookController::class, 'index'])
        ->name('guest-book.index');
    Route::post('/{slug}/rsvp', [RsvpController::class, 'store'])
        ->name('rsvp.store');
});