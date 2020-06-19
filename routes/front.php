<?php

use App\Http\Auth\Controllers\ForgotPasswordController;
use App\Http\Auth\Controllers\LoginController;
use App\Http\Auth\Controllers\ProfileController;
use App\Http\Auth\Controllers\RegisterController;
use App\Http\Auth\Controllers\ResetPasswordController;
use App\Http\Front\Controllers\GithubSocialiteController;
use App\Http\Auth\Controllers\LogoutController;
use App\Http\Front\Controllers\OpenSourceController;
use App\Http\Front\Controllers\PostcardController;
use App\Http\Front\Controllers\ProductsController;
use App\Http\Front\Controllers\Videos\ShowVideoController;
use App\Http\Front\Controllers\Videos\VideoIndexController;
use App\Http\Front\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::mailcoach('mailcoach');

Route::post('paddle/webhook', WebhookController::class);

Route::view('/', 'front.pages.home.index')->name('home');

Route::view('web-development', 'front.pages.web-development.index')->name('web-development');

Route::prefix('about-us')->group(function () {
    Route::view('/', 'front.pages.about.index')->name('about');

    collect(config('team.members'))->each(function (string $personName) {
        Route::permanentRedirect($personName, "/about-us/#{$personName}");
    });
});

Route::prefix('products')->group(function () {
    Route::get('/', [ProductsController::class, 'index'])->name('products.index');
    Route::get('{product:slug}', [ProductsController::class, 'show'])->name('products.show');
});

Route::prefix('open-source')->group(function () {
    Route::get('/', [OpenSourceController::class, 'index'])->name('open-source.index');
    Route::get('postcards', [PostcardController::class, 'index'])->name('open-source.postcards');
    Route::get('packages', [OpenSourceController::class, 'packages'])->name('open-source.packages');
    Route::get('projects', [OpenSourceController::class, 'projects'])->name('open-source.projects');
    Route::get('support-us', [OpenSourceController::class, 'support'])->name('open-source.support');
});

Route::prefix('vacancies')->group(function () {
    Route::permanentRedirect('free-application', '/vacancies/spontaneous-application');

    Route::view('/', 'front.pages.vacancies.index')->name('vacancies.index');
    Route::view('internships', 'front.pages.vacancies.internship')->name('vacancies.internship');

    Route::get('{slug}', function ($slug) {
        $view = "front.pages.vacancies.{$slug}";

        if (! view()->exists($view)) {
            abort(404);
        }

        return view("front.pages.vacancies.{$slug}");
    })->name('vacancies.show');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile');
    Route::get('disconnect', [ProfileController::class, 'disconnect'])->name('github-disconnect');
    Route::delete('profile', [ProfileController::class, 'delete'])->name('profile');
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('forgot-password');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('login/github', [GithubSocialiteController::class, 'redirect'])->name('github-login');
Route::get('login/github/callback', [GithubSocialiteController::class, 'callback']);
Route::post('logout', [LogoutController::class, 'logout'])->name('logout');

Route::get('/videos', VideoIndexController::class)->name('videos.index');
Route::get('/videos/{series:slug}/{video:slug}', ShowVideoController::class)->name('videos.show');

Route::view('legal', 'front.pages.legal.index')->name('legal.index');
Route::view('privacy', 'front.pages.legal.privacy')->name('legal.privacy');
Route::view('disclaimer', 'front.pages.legal.disclaimer')->name('legal.disclaimer');
Route::view('general-conditions', 'front.pages.legal.generalConditions')->name('legal.conditions');
Route::view('gdpr', 'front.pages.legal.gdpr')->name('legal.gdpr');

Route::view('offline', 'errors.offline')->name('offline');
