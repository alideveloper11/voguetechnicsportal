<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\CustomerTypeController;
use App\Http\Controllers\QuoteController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Route::get('/test', function () {
//     return view('admin.quotes_management.create');
// })->name('test');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('quotes')->name('quotes.')->group(function () {
        Route::get('web-inquiries', [QuoteController::class, 'webInquiries'])->name('web-inquiries');
        Route::get('updated-quotes', [QuoteController::class, 'updatedQuotes'])->name('updated-quotes');
        Route::get('accepted-quotes', [QuoteController::class, 'acceptedQuotes'])->name('accepted-quotes');
        Route::get('quote/{quote}/city-distance', [QuoteController::class, 'cityDistance'])->name('quote.city-distance');
        Route::get('quote/{quote}/email-log/{emailLog}', [QuoteController::class, 'emailLog'])->name('quote.email-log');
        Route::post('quote/preview-email', [QuoteController::class, 'previewEmail'])->name('quote.preview-email');
        Route::post('quote/vrm-search', [QuoteController::class, 'vrmSearch'])->name('quote.vrm-search');
        Route::post('quote/{quote}/send-email', [QuoteController::class, 'sendEmail'])->name('quote.send-email');
        Route::post('quote/{quote}/archive', [QuoteController::class, 'archiveQuote'])->name('quote.archive');
        Route::resource('quote', QuoteController::class);
    });

    // Website
    Route::resource('websites', WebsiteController::class);

    // Banks
    Route::resource('banks', BankController::class);

    // Email Templates
    Route::resource('email-templates', EmailTemplateController::class);
    Route::post('email-templates/upload-image', [EmailTemplateController::class, 'uploadImage'])->name('email-templates.upload-image');
    Route::get('email-templates/{email_template}/send-test', [EmailTemplateController::class, 'sendTest'])->name('email-templates.send-test');


    // Customer Types
    Route::resource('customer-types', CustomerTypeController::class);

    // Roles
    Route::resource('roles', RoleController::class);

    // User
    Route::resource('users', UserController::class);
});


require __DIR__.'/auth.php';
