<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\TahapController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\JadpikController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


// Grouping all routes under the auth middleware
Route::middleware(['auth'])->group(function () {
    
    // Protected routes that require authentication
    Route::resource('absens', AbsenController::class);
    Route::resource('izins', IzinController::class);
    Route::resource('jurnals', JurnalController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('tahap', TahapController::class);
    Route::resource('jadpik', JadpikController::class);
    
    // Divisi routes (CRUD operations)
    Route::get('/divisi', [DivisiController::class, 'index'])->name('divisi.index');
    Route::post('/divisi', [DivisiController::class, 'store'])->name('divisi.store');
    Route::put('/divisi/{id}', [DivisiController::class, 'update'])->name('divisi.update');
    Route::delete('/divisi/{id}', [DivisiController::class, 'destroy'])->name('divisi.destroy');
    
    // Profile management routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard route with verified middleware for additional protection
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');
    
});

// Routes that don't require authentication
Route::get('/cobalogin', function () {
    return view('login');
});

Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');

Route::get('/navigation', function () {
    return view('layouts.navigation');
})->name('navigation');



// Halaman untuk Admin (hanya bisa diakses oleh admin)
Route::get('/admin', function () {
    // Cek apakah pengguna sudah login dan apakah mereka memiliki role 'admin'
    $user = Auth::user();
    
    // Pastikan pengguna terdaftar dan memiliki role admin
    if ($user && $user->role === 'admin') {
        return view('admin.dashboard'); // Halaman khusus admin
    } else {
        // Redirect ke halaman lain jika bukan admin atau pengguna belum login
        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
});

// // Halaman untuk Member (hanya bisa diakses oleh member)
// Route::get('/member', function () {
//     // Cek apakah pengguna sudah login dan apakah mereka memiliki role 'member'
//     $user = Auth::user();
    
//     // Pastikan pengguna terdaftar dan memiliki role member
//     if ($user && $user->role === 'member') {
//         return view('member.dashboard'); // Halaman khusus member
//     } else {
//         // Redirect ke halaman lain jika bukan member atau pengguna belum login
//         return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
//     }
// });



require __DIR__.'/auth.php';
