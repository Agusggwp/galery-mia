<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberFormController;
use App\Http\Controllers\MediaStreamController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AlbumController as AdminAlbumController;
use App\Http\Controllers\Admin\MediaController as AdminMediaController;
use App\Http\Controllers\Admin\AdminMemberController;
use App\Http\Controllers\Admin\MemberInvitationController;
use App\Http\Controllers\Admin\GoogleDriveController;
use App\Http\Controllers\Admin\SettingController;

// Public Gallery Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/album/{slug}', [AlbumController::class, 'show'])->name('album.show');

// Public Member Routes
Route::get('/anggota', [MemberController::class, 'index'])->name('members.index');
Route::get('/anggota/{slug}', [MemberController::class, 'show'])->name('members.show');

// Public Member Joining Form Routes
Route::get('/join/{token}', [MemberFormController::class, 'show'])->name('member.join');
Route::post('/join/{token}', [MemberFormController::class, 'store'])->name('member.join.store');

// Media Proxy & Thumbnail Streaming Routes
Route::get('/media/{id}/thumbnail', [MediaStreamController::class, 'thumbnail'])->name('media.thumbnail');
Route::get('/media/{id}/stream', [MediaStreamController::class, 'stream'])->name('media.stream');

// Admin Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Admin Panel Routes
Route::middleware('auth')->prefix('admin')->as('admin.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Albums Management
    Route::get('/albums', [AdminAlbumController::class, 'index'])->name('albums.index');
    Route::put('/albums/{album}', [AdminAlbumController::class, 'update'])->name('albums.update');
    Route::patch('/albums/{album}/toggle', [AdminAlbumController::class, 'toggleVisibility'])->name('albums.toggle');

    // Media Management
    Route::get('/media', [AdminMediaController::class, 'index'])->name('media.index');
    Route::patch('/media/{media}/toggle', [AdminMediaController::class, 'toggleVisibility'])->name('media.toggle');

    // Members Management
    Route::resource('members', AdminMemberController::class);
    Route::post('/members/{member}/approve', [AdminMemberController::class, 'approve'])->name('members.approve');
    Route::post('/members/{member}/reject', [AdminMemberController::class, 'reject'])->name('members.reject');
    Route::patch('/members/{member}/toggle', [AdminMemberController::class, 'toggle'])->name('members.toggle');

    // Member Invitations Link Management
    Route::resource('member-invitations', MemberInvitationController::class);
    Route::post('/member-invitations/{member_invitation}/toggle', [MemberInvitationController::class, 'toggle'])->name('member-invitations.toggle');

    // Google Drive Sync
    Route::get('/google-drive', [GoogleDriveController::class, 'index'])->name('google-drive');
    Route::post('/google-drive/sync', [GoogleDriveController::class, 'sync'])->name('google-drive.sync');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});
