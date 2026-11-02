<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;
use Maina888\OneTap\Http\Controllers\OneTapController;
use Maina888\OneTap\Http\Controllers\Settings\OneTapSettingController;

AdminHelper::registerRoutes(function (): void {
    Route::group(['prefix' => 'settings/one-tap', 'as' => 'one-tap.settings', 'permission' => 'one-tap.settings'], function (): void {
        Route::get('/', [OneTapSettingController::class, 'edit']);
        Route::put('/', [OneTapSettingController::class, 'update'])->name('.update');
    });
});

Theme::registerRoutes(function (): void {
    Route::post('one-tap/login', [OneTapController::class, 'login'])->middleware('throttle:20,1')->name('one-tap.login');
});
