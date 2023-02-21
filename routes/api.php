<?php

use App\Http\Controllers\Mobile\Content\AssetController;
use App\Http\Controllers\Mobile\Content\TitleController;
use App\Http\Controllers\Mobile\MainController as MobileMainController;
use App\Http\Controllers\Mobile\ChartController as MobileChartController;
use App\Http\Controllers\Mobile\ArticleController as MobileArticleController;
use App\Http\Controllers\Mobile\PodcastController as MobilePodcastController;

use App\Http\Controllers\Website\ArticleController;
use App\Http\Controllers\Website\ChartController;
use App\Http\Controllers\Website\EventController;
use App\Http\Controllers\Website\JockController;
use App\Http\Controllers\Website\MainController;
use App\Http\Controllers\Website\MessageController;
use App\Http\Controllers\Website\PodcastController;
use App\Http\Controllers\Website\ScholarController;
use App\Http\Controllers\Website\ShowController;


use App\Http\Controllers\Website\Content\Admin\DesignationController;
use App\Http\Controllers\Website\Content\Admin\EmployeeController;
use App\Http\Controllers\Website\Content\Radio1\BatchController;
use App\Http\Controllers\Website\Content\Programs\JockController as CMSJockController;
use App\Http\Controllers\Website\Content\Radio1\JockController as R1JocksController;


use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('user', function (Request $request) {
    return $request->user();
});*/

Route::prefix('mobile')->group(function() {
    Route::prefix('cms')->group(function() {
        Route::prefix('assets')->group(function() {
            Route::get('', [AssetController::class, 'index'])->name('asset.index');
            Route::get('/show/{id}', [AssetController::class, 'show'])->name('asset.show');
            Route::post('/store', [AssetController::class, 'store'])->name('asset.store');
            Route::post('/update/{id}', [AssetController::class, 'update'])->name('asset.update');
            Route::delete('/delete/{id}', [AssetController::class, 'destroy'])->name('asset.destroy');
            Route::post('/upload', [AssetController::class, 'uploadImage'])->name('asset.upload-image');
        });

        Route::prefix('titles')->group(function() {
            Route::get('', [TitleController::class, 'index'])->name('title.index');
            Route::get('/show/{id}', [TitleController::class, 'show'])->name('title.show');
            Route::post('/store', [TitleController::class, 'store'])->name('title.store');
            Route::post('/update/{id}', [TitleController::class, 'update'])->name('title.update');
            Route::delete('/delete/{id}', [TitleController::class, 'destroy'])->name('title.destroy');
        });
    });

    Route::get('', [MobileMainController::class, 'index'])->name('home');

    Route::get('live', [MobileMainController::class, 'live'])->name('live');

    Route::get('charts', [MobileChartController::class, 'index'])->name('charts');
    Route::get('charts/vote', [MobileChartController::class, 'vote'])->name('vote');

    Route::get('articles', [MobileArticleController::class, 'index'])->name('articles');
    Route::get('article/{id}', [MobileArticleController::class, 'view'])->name('view.article');

    Route::get('podcasts', [MobilePodcastController::class, 'index'])->name('podcasts');
    Route::get('podcast/{id}', [MobilePodcastController::class, 'view'])->name('view.podcast');

    Route::get('youtube/{max}', [MobileMainController::class, 'youTube'])->name('youtube');

    Route::get('search', [MobileMainController::class, 'search'])->name('search');
    Route::get('browse', [MobileMainController::class, 'browse'])->name('browse');
    Route::get('help', [MobileMainController::class, 'help'])->name('help');
    Route::get('about', [MobileMainController::class, 'about'])->name('about');
});

Route::prefix('website')->group(function() {
    Route::prefix('cms')->group(function() {
        Route::prefix('designations')->group(function () {
            Route::get('', [DesignationController::class, 'index'])->name('designations');
            Route::get('show/{id}', [DesignationController::class, 'show'])->name('designation.show');
            Route::post('store', [DesignationController::class, 'store'])->name('designation.store');
            Route::post( 'update/{id}', [DesignationController::class, 'update'])->name('designation.update');
            Route::delete('delete/{id}', [DesignationController::class, 'destroy'])->name('designation.delete');
        });

        Route::prefix('employees')->group(function() {
            Route::get('', [EmployeeController::class, 'index'])->name('employee.index');
            Route::get('show/{id}', [EmployeeController::class, 'show'])->name('employee.show');
            Route::post('store', [EmployeeController::class, 'store'])->name('employee.store');
            Route::post('update/{id}', [EmployeeController::class, 'update'])->name('employee.update');
            Route::delete('delete/{id}', [EmployeeController::class, 'destroy'])->name('employee.destroy');
        });

        Route::prefix('jocks')->group(function() {
            Route::get('', [CMSJockController::class, 'index'])->name('jock.index');
            Route::get('show/{id}', [CMSJockController::class, 'show'])->name('jock.show');
            Route::post('store', [CMSJockController::class, 'store'])->name('jock.store');
            Route::post('update/{id}', [CMSJockController::class, 'update'])->name('jock.update');
            Route::delete('delete/{id}', [CMSJockController::class, 'destroy'])->name('jock.destroy');
        });

        Route::prefix('radio1')->group(function() {
            Route::prefix('batch')->group(function() {
                Route::get('', [BatchController::class, 'index'])->name('radio1.batch.index');
                Route::get('show/{id}', [BatchController::class, 'show'])->name('radio1.batch.show');
                Route::post('store', [BatchController::class, 'store'])->name('radio1.batch.store');
                Route::post('update/{id}', [BatchController::class, 'update'])->name('radio1.batch.update');
                Route::delete('delete/{id}', [BatchController::class, 'destroy'])->name('radio1.batch.destroy');
            });

            Route::prefix('jocks')->group(function() {
                Route::get('', [R1JocksController::class, 'index'])->name('r1jock..index');
                Route::get('show/{id}', [R1JocksController::class, 'show'])->name('r1jock..show');
                Route::post('store', [R1JocksController::class, 'store'])->name('r1jock..store');
                Route::post('update/{id}', [R1JocksController::class, 'update'])->name('r1jock..update');
                Route::delete('delete/{id}', [R1JocksController::class, 'destroy'])->name('r1jock..destroy');
            });
        });
    });

    Route::get('', [MainController::class, 'home'])->name('home');
    Route::get('live', [MainController::class, 'liveStream'])->name('live');

    Route::get('articles', [ArticleController::class, 'index'])->name('articles');
    Route::get('article/{article_guid}', [ArticleController::class, 'show'])->name('view.article');

    Route::get('podcasts', [PodcastController::class, 'index'])->name('podcasts');
    Route::get('podcast/{id}', [PodcastController::class, 'show'])->name('view.podcast');

    Route::get('wallpapers', [MainController::class, 'wallpapers'])->name('wallpapers');

    Route::get('shows', [ShowController::class, 'index'])->name('shows');
    Route::get('show/{slug_string}', [ShowController::class, 'view'])->name('view.show');

    Route::get('jocks', [JockController::class, 'index'])->name('jocks');
    Route::get('jock/{slug_string}', [JockController::class, 'view'])->name('view.jock');
    Route::get('radio1', [JockController::class, 'radio1'])->name('studentJocks');

    Route::get('charts', [ChartController::class, 'index'])->name('charts');
    Route::get('daily', [ChartController::class, 'daily'])->name('daily.charts');
    Route::get('allTime', [ChartController::class, 'allTimeHit'])->name('all.time');
    Route::get('southside', [ChartController::class, 'southside'])->name('southside.sounds');
    Route::get('outbreaks', [ChartController::class, 'outbreaks'])->name('chart.outbreaks');

    Route::get('indiegrounds', [ChartController::class, 'indieground'])->name('indieground');
    Route::get('song/{id}', [ChartController::class, 'getSongData'])->name('get.song');

    Route::get('events', [EventController::class, 'index'])->name('gimikboard');
    Route::get('event/{id}', [EventController::class, 'view'])->name('view.gimikboard');

    Route::get('scholars', [ScholarController::class, 'index'])->name('scholars');
    Route::get('scholars/batch/{batch_number}', [ScholarController::class, 'view'])->name('scholar.view');

    Route::post('send', [MessageController::class, 'index'])->name('send.message');
});
