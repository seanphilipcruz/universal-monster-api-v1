<?php

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::prefix('mobile')->group(function() {
    Route::get('/', [MobileMainController::class, 'index'])->name('home');

    Route::get('/live', [MobileMainController::class, 'live'])->name('live');

    Route::get('/charts', [MobileChartController::class, 'index'])->name('charts');
    Route::get('/charts/vote', [MobileChartController::class, 'vote'])->name('vote');

    Route::get('/articles', [MobileArticleController::class, 'index'])->name('articles');
    Route::get('/article/{id}', [MobileArticleController::class, 'view'])->name('view.article');

    Route::get('/podcasts', [MobilePodcastController::class, 'index'])->name('podcasts');
    Route::get('/podcast/{id}', [MobilePodcastController::class, 'view'])->name('view.podcast');

    Route::get('/youtube/{max}', [MobileMainController::class, 'youTube'])->name('youtube');

    Route::get('/search', [MobileMainController::class, 'search'])->name('search');
    Route::get('/browse', [MobileMainController::class, 'browse'])->name('browse');
    Route::get('/help', [MobileMainController::class, 'help'])->name('help');
    Route::get('/about', [MobileMainController::class, 'about'])->name('about');
});

Route::prefix('website')->group(function() {
    Route::get('/', [MainController::class, 'home'])->name('home');
    Route::get('/live', [MainController::class, 'liveStream'])->name('live');

    Route::get('/articles', [ArticleController::class, 'index'])->name('articles');
    Route::get('/article/{article_guid}', [ArticleController::class, 'show'])->name('view.article');

    Route::get('/podcasts', [PodcastController::class, 'index'])->name('podcasts');
    Route::get('/podcast/{id}', [PodcastController::class, 'show'])->name('view.podcast');

    Route::get('/wallpapers', [MainController::class, 'wallpapers'])->name('wallpapers');

    Route::get('/shows', [ShowController::class, 'index'])->name('shows');
    Route::get('/show/{slug_string}', [ShowController::class, 'view'])->name('view.show');

    Route::get('/jocks', [JockController::class, 'index'])->name('jocks');
    Route::get('/jock/{slug_string}', [JockController::class, 'view'])->name('view.jock');
    Route::get('/radio1', [JockController::class, 'radio1'])->name('studentJocks');

    Route::get('/charts', [ChartController::class, 'index'])->name('charts');
    Route::get('/daily', [ChartController::class, 'daily'])->name('daily.charts');
    Route::get('/allTime', [ChartController::class, 'allTimeHit'])->name('all.time');
    Route::get('/southside', [ChartController::class, 'southside'])->name('southside.sounds');
    Route::get('/outbreaks', [ChartController::class, 'outbreaks'])->name('chart.outbreaks');

    Route::get('/indiegrounds', [ChartController::class, 'indieground'])->name('indieground');
    Route::get('/song/{id}', [ChartController::class, 'getSongData'])->name('get.song');

    Route::get('/events', [EventController::class, 'index'])->name('gimikboard');
    Route::get('/event/{id}', [EventController::class, 'view'])->name('view.gimikboard');

    Route::get('/scholars', [ScholarController::class, 'index'])->name('scholars');
    Route::get('/scholars/batch/{batch_number}', [ScholarController::class, 'view'])->name('scholar.view');

    Route::post('/send', [MessageController::class, 'index'])->name('send.message');
});
