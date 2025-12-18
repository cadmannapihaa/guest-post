<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SubscriptionPackageController;
use App\Http\Controllers\PostEditAccessController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\LoginLogController;

/*
|--------------------------------------------------------------------------
| Guest Routes (No Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    // Registration
    Route::get('register', [AuthController::class, 'showRegistration'])
        ->name('register.show');
    Route::post('register', [AuthController::class, 'register'])
        ->name('register.perform');

    // Login
    Route::get('login', [AuthController::class, 'showLogin'])
        ->name('login.show');
    Route::post('login', [AuthController::class, 'login'])
        ->name('login.perform');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (User Must Be Logged In)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('logout', [AuthController::class, 'logout'])
        ->name('logout');

    // Home Redirect
    Route::get('/', fn() => redirect()->route('posts.index'));

    /*
    |--------------------------------------------------------------------------
    | Post CRUD (All Authenticated)
    |--------------------------------------------------------------------------
    */
    Route::resource('posts', PostController::class);

    /*
    |--------------------------------------------------------------------------
    | Comment CRUD (Nested under posts, shallow routes for others)
    |--------------------------------------------------------------------------
    */
    Route::resource('posts.comments', CommentController::class)
        ->shallow();

    /*
    |--------------------------------------------------------------------------
    | Post Edit Access (Time-Bound Permissions)
    |--------------------------------------------------------------------------
    */
    Route::middleware('permission:grant post edit access')->group(function () {
        Route::post('post-edit-access/grant', [PostEditAccessController::class, 'grant'])
            ->name('postedit.grant');

        Route::post('post-edit-access/revoke', [PostEditAccessController::class, 'revoke'])
            ->name('postedit.revoke');
    });

    /*
    |--------------------------------------------------------------------------
    | Subscription Package Management (Admin Only)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('subscription-packages')->group(function () {
        Route::get('/', [SubscriptionPackageController::class, 'index'])
            ->name('subscription-packages.index');

        Route::get('create', [SubscriptionPackageController::class, 'create'])
            ->name('subscription-packages.create');

        Route::post('', [SubscriptionPackageController::class, 'store'])
            ->name('subscription-packages.store');

        Route::get('edit/{subscriptionPackage}', [SubscriptionPackageController::class, 'edit'])
            ->name('subscription-packages.edit');

        Route::put('{subscriptionPackage}', [SubscriptionPackageController::class, 'update'])
            ->name('subscription-packages.update');

        Route::delete('{subscriptionPackage}', [SubscriptionPackageController::class, 'destroy'])
            ->name('subscription-packages.destroy');

        // Assign a subscription package to user
        Route::post('assign', [SubscriptionPackageController::class, 'assignToUser'])
            ->name('subscription-packages.assign');
    });

    /*
    |--------------------------------------------------------------------------
    | Sessions (View & Revoke Active Sessions)
    |--------------------------------------------------------------------------
    */
    Route::get('sessions', [SessionController::class, 'index'])
        ->name('sessions.index');

    Route::delete('sessions/{id}', [SessionController::class, 'destroy'])
        ->name('sessions.destroy');

    /*
    |--------------------------------------------------------------------------
    | Login Logs (View Login Attempts, Admin Only)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->get('login-logs', [LoginLogController::class, 'index'])
        ->name('loginlogs.index');


    //-------------------------------------
    Route::get('notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('notifications/{notification}/read', [NotificationController::class, 'markRead'])
        ->name('notifications.markRead');

    Route::post('notifications/read-all', [NotificationController::class, 'markAllRead'])
        ->name('notifications.markAllRead');

});


Route::middleware('role:admin')->group(function () {
    Route::resource('tags', TagController::class);
});
