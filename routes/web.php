<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CreatorController;
use App\Models\Genre;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    // For non-authenticated users, show dashboard with comics
    $genres = \App\Models\Genre::all();

    try {
        // Fetch top comics by rating (simplified for now)
        $topComics = \App\Models\Comic::published()
            ->with(['user', 'ratings'])
            ->orderBy('created_at', 'desc') // Temporary: order by date instead of rating
            ->limit(10)
            ->get();

        // Fetch new released comics
        $newReleasedComics = \App\Models\Comic::published()
            ->with(['user', 'ratings'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Fetch random comics for "For You"
        $forYouComics = \App\Models\Comic::published()
            ->with(['user', 'ratings'])
            ->inRandomOrder()
            ->limit(10)
            ->get();
    } catch (\Exception $e) {
        // If there's an error with the queries, return empty collections
        $topComics = collect();
        $newReleasedComics = collect();
        $forYouComics = collect();
    }

    return view('user/dashboard', compact('genres', 'topComics', 'newReleasedComics', 'forYouComics'));
})->name('home');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// OTP Routes
Route::get('/otp/{userId?}', [OtpController::class, 'showOtpForm'])->name('otp.verify');
Route::get('/otp/send/init', [OtpController::class, 'sendOtpForRegistration'])->name('otp.send.init');
Route::post('/otp/send', [OtpController::class, 'sendOtp'])->name('otp.send');
Route::post('/otp/verify', [OtpController::class, 'verifyOtp'])->name('otp.verify.post');

// Dashboard Route (accessible without login)
Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

// Ranking Route (accessible without login)
Route::get('/ranking', [UserController::class, 'ranking'])->name('ranking');

use App\Http\Controllers\ComicController;

// Comic Routes
Route::get('/comics', [ComicController::class, 'index'])->name('comics.all');
Route::get('/category/{genreSlug?}', [ComicController::class, 'category'])->name('comics.category');
Route::get('/comic/{id}', [ComicController::class, 'show'])->name('comic.detail');
Route::get('/comic/{id}/read/{chapter?}', [ComicController::class, 'read'])->name('comic.read');

// Payment Routes
Route::middleware('auth')->group(function () {
    Route::get('/comic/{id}/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/comic/{id}/payment', [PaymentController::class, 'createPayment'])->name('payment.create');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failed', [PaymentController::class, 'failed'])->name('payment.failed');
    Route::get('/payment/unfinished', [PaymentController::class, 'unfinished'])->name('payment.unfinished');
    Route::get('/payment/status/{orderId}', [PaymentController::class, 'checkStatus'])->name('payment.status');
});

// Midtrans Webhook (no auth required)
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');

// Protected Routes
Route::middleware('auth')->group(function () {

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Manage Comics
        Route::get('/manage-comic', [AdminController::class, 'manageComics'])->name('comics.index');
        Route::get('/manage-comic/create', [AdminController::class, 'createComic'])->name('comics.create');
        Route::post('/manage-comic', [AdminController::class, 'storeComic'])->name('comics.store');
        Route::get('/manage-comic/{id}/edit', [AdminController::class, 'editComic'])->name('comics.edit');
        Route::put('/manage-comic/{id}', [AdminController::class, 'updateComic'])->name('comics.update');
        Route::delete('/manage-comic/{id}', [AdminController::class, 'deleteComic'])->name('comics.destroy');
        Route::post('/manage-comic/{id}/approve', [AdminController::class, 'approveComic'])->name('comics.approve');
        Route::post('/manage-comic/{id}/reject', [AdminController::class, 'rejectComic'])->name('comics.reject');

        // Manage Chapters (Episodes)
        Route::get('/manage-comic/{comicId}/create-chapter', [AdminController::class, 'createChapter'])->name('comics.createChapter');
        Route::post('/manage-comic/{comicId}/create-chapter', [AdminController::class, 'storeChapter'])->name('comics.storeChapter');

        // Manage Genres
        Route::get('/manage-genre/index', [AdminController::class, 'manageGenres'])->name('genres.index');
        Route::get('/manage-genre/create', [AdminController::class, 'createGenre'])->name('genres.create');
        Route::post('/manage-genre', [AdminController::class, 'storeGenre'])->name('genres.store');
        Route::get('/manage-genre/{id}/edit', [AdminController::class, 'editGenre'])->name('genres.edit');
        Route::put('/manage-genre/{id}', [AdminController::class, 'updateGenre'])->name('genres.update');
        Route::delete('/manage-genre/{id}', [AdminController::class, 'deleteGenre'])->name('genres.destroy');

        // Manage Users
        Route::get('/manage-user', [AdminController::class, 'manageUsers'])->name('users.index');
        Route::get('/manage-user/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/manage-user', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/manage-user/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/manage-user/{id}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/manage-user/{id}', [AdminController::class, 'deleteUser'])->name('users.destroy');
    });

    // Creator Routes
    Route::prefix('creator')->name('creator.')->group(function () {
        Route::get('/onboarding', function () {
            return view('creator.onboarding');
        })->name('onboarding');
        Route::get('/dashboard', [CreatorController::class, 'dashboard'])->name('dashboard');

        // Manage Comics
        Route::get('/manage-comic', [CreatorController::class, 'manageComics'])->name('comics.index');
        Route::get('/manage-comic/create', [CreatorController::class, 'createComic'])->name('comics.create');
        Route::get('/manage-comic/{id}', [CreatorController::class, 'showComic'])->name('comics.show');
        Route::post('/manage-comic', [CreatorController::class, 'storeComic'])->name('comics.store');
        Route::get('/manage-comic/{id}/edit', [CreatorController::class, 'editComic'])->name('comics.edit');
        Route::put('/manage-comic/{id}', [CreatorController::class, 'updateComic'])->name('comics.update');
        Route::delete('/manage-comic/{id}', [CreatorController::class, 'deleteComic'])->name('comics.destroy');

        // Manage Chapters
        Route::get('/manage-comic/{comicId}/create-chapter', [CreatorController::class, 'createChapter'])->name('comics.createChapter');
        Route::post('/manage-comic/{comicId}/create-chapter', [CreatorController::class, 'storeChapter'])->name('comics.storeChapter');
        Route::get('/manage-comic/{comicId}/chapter/{chapterId}/edit', [CreatorController::class, 'editChapter'])->name('comics.editChapter');
        Route::put('/manage-comic/{comicId}/chapter/{chapterId}', [CreatorController::class, 'updateChapter'])->name('comics.updateChapter');
        Route::delete('/manage-comic/{comicId}/chapter/{chapterId}', [CreatorController::class, 'deleteChapter'])->name('comics.deleteChapter');
    });

    // User Profile Routes
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');

    // Following and History Routes
    Route::get('/following', [UserController::class, 'following'])->name('user.following');

    Route::get('/history', function () {
        return view('user/user-menu/user-history');
    })->name('user.history');

    // Become Creator Route
    Route::post('/become-creator', [UserController::class, 'becomeCreator'])->name('become.creator');
});

Route::get('/signup', function () {
    return view('signup');
});

Route::get('/ranking', function () {
    return view('ranking');
});

