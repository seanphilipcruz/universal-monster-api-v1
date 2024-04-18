<?php

use App\Http\Controllers\Mobile\Content\AssetController;
use App\Http\Controllers\Mobile\Content\TitleController;
use App\Http\Controllers\Mobile\MainController as MobileMainController;
use App\Http\Controllers\Mobile\ChartController as MobileChartController;
use App\Http\Controllers\Mobile\ArticleController as MobileArticleController;
use App\Http\Controllers\Mobile\PodcastController as MobilePodcastController;

use App\Http\Controllers\Website\ArticleController;
use App\Http\Controllers\Website\ChartController;
use App\Http\Controllers\Website\Content\Admin\AwardsController;
use App\Http\Controllers\Website\Content\Admin\BugController;
use App\Http\Controllers\Website\EventController;
use App\Http\Controllers\Website\JockController;
use App\Http\Controllers\Website\MainController;
use App\Http\Controllers\Website\MessageController;
use App\Http\Controllers\Website\PodcastController;
use App\Http\Controllers\Website\ScholarController;
use App\Http\Controllers\Website\ShowController;


use App\Http\Controllers\Website\Content\Admin\DesignationController;
use App\Http\Controllers\Website\Content\Admin\EmployeeController;
use App\Http\Controllers\Website\Content\Admin\MessageController as CMSMessageController;
use App\Http\Controllers\Website\Content\Admin\UserController as CMSUsersController;
use App\Http\Controllers\Website\Content\Blogs\ArticleController as CMSArticleController;
use App\Http\Controllers\Website\Content\Blogs\CategoryController;
use App\Http\Controllers\Website\Content\Blogs\RelatedArticleController;
use App\Http\Controllers\Website\Content\Blogs\SubArticleContentController;
use App\Http\Controllers\Website\Content\Charts\AlbumController;
use App\Http\Controllers\Website\Content\Charts\ArtistController;
use App\Http\Controllers\Website\Content\Charts\ChartController as CMSChartController;
use App\Http\Controllers\Website\Content\Charts\GenreController;
use App\Http\Controllers\Website\Content\Charts\OutbreakController;
use App\Http\Controllers\Website\Content\Charts\SongController;
use App\Http\Controllers\Website\Content\Charts\SouthsideController;
use App\Http\Controllers\Website\Content\Digital\HeaderController;
use App\Http\Controllers\Website\Content\Digital\PhotoController;
use App\Http\Controllers\Website\Content\Digital\WallpaperController;
use App\Http\Controllers\Website\Content\Education\Scholar\BatchController as ScholarBatchController;
use App\Http\Controllers\Website\Content\Education\Scholar\SponsorController;
use App\Http\Controllers\Website\Content\Education\Scholar\StudentController;
use App\Http\Controllers\Website\Content\Education\GimikboardController;
use App\Http\Controllers\Website\Content\Education\SchoolController;
use App\Http\Controllers\Website\Content\Indieground\ArtistController as IndiegroundController;
use App\Http\Controllers\Website\Content\Indieground\FeaturedArtistController;
use App\Http\Controllers\Website\Content\Programs\JockController as CMSJockController;
use App\Http\Controllers\Website\Content\Programs\PodcastController as CMSPodcastController;
use App\Http\Controllers\Website\Content\Programs\ShowController as CMSShowController;
use App\Http\Controllers\Website\Content\Programs\TimeslotController;
use App\Http\Controllers\Website\Content\Promo\ContestantController;
use App\Http\Controllers\Website\Content\Promo\GiveawayController;
use App\Http\Controllers\Website\Content\Radio1\BatchController;
use App\Http\Controllers\Website\Content\Radio1\JockController as R1JocksController;
use App\Http\Controllers\Website\Content\DashboardController;

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

    Route::get('asset/{id}', [MobileMainController::class, 'assets'])->name('dynamic.assets');
    Route::get('search', [MobileMainController::class, 'search'])->name('search');
    Route::get('browse', [MobileMainController::class, 'browse'])->name('browse');
    Route::get('help', [MobileMainController::class, 'help'])->name('help');
    Route::get('about', [MobileMainController::class, 'about'])->name('about');
});

Route::prefix('website')->group(function() {
    Route::prefix('cms')->group(function() {
        Route::prefix('awards')->group(function () {
            Route::get('', [AwardsController::class, 'index'])->name('awards');
            Route::get('show/{id}', [AwardsController::class, 'show'])->name('awards.show');
            Route::post('store', [AwardsController::class, 'store'])->name('awards.store');
            Route::post( 'update/{id}', [AwardsController::class, 'update'])->name('awards.update');
            Route::delete('delete/{id}', [AwardsController::class, 'destroy'])->name('awards.delete');
        });

        Route::prefix('bugs')->group(function () {
            Route::get('', [BugController::class, 'index'])->name('bugs');
            Route::get('show/{id}', [BugController::class, 'show'])->name('bugs.show');
            Route::post('store', [BugController::class, 'store'])->name('bugs.store');
            Route::post( 'update/{id}', [BugController::class, 'update'])->name('bugs.update');
            Route::delete('delete/{id}', [BugController::class, 'destroy'])->name('bugs.delete');
            Route::post('upload/image', [BugController::class, 'upload_image'])->name('bugs.upload_image');
        });

        Route::prefix('designations')->group(function () {
            Route::get('', [DesignationController::class, 'index'])->name('designations');
            Route::get('show/{id}', [DesignationController::class, 'show'])->name('designations.show');
            Route::post('store', [DesignationController::class, 'store'])->name('designations.store');
            Route::post( 'update/{id}', [DesignationController::class, 'update'])->name('designations.update');
            Route::delete('delete/{id}', [DesignationController::class, 'destroy'])->name('designations.delete');
        });

        Route::prefix('employees')->group(function() {
            Route::get('', [EmployeeController::class, 'index'])->name('employees.index');
            Route::get('show/{id}', [EmployeeController::class, 'show'])->name('employees.show');
            Route::post('store', [EmployeeController::class, 'store'])->name('employees.store');
            Route::post('update/{id}', [EmployeeController::class, 'update'])->name('employees.update');
            Route::delete('delete/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
        });

        Route::prefix('messages')->group(function() {
            Route::get('', [CMSMessageController::class, 'index'])->name('messages.index');
            Route::get('show/{id}', [CMSMessageController::class, 'show'])->name('messages.show');
            Route::post('update/{id}', [CMSMessageController::class, 'update'])->name('messages.update');
            Route::delete('delete/{id}', [CMSMessageController::class, 'destroy'])->name('messages.destroy');
        });

        Route::prefix('users')->group(function() {
            Route::get('', [CMSUsersController::class, 'index'])->name('users.index');
            Route::get('show/{id}', [CMSUsersController::class, 'show'])->name('users.show');
            Route::post('store', [CMSUsersController::class, 'store'])->name('users.store');
            Route::post('update/{id}', [CMSUsersController::class, 'update'])->name('users.update');
            Route::delete('delete/{id}', [CMSUsersController::class, 'destroy'])->name('users.destroy');
        });

        Route::prefix('articles')->group(function() {
            Route::get('', [CMSArticleController::class, 'index'])->name('articles.index');
            Route::get('show/{id}', [CMSArticleController::class, 'show'])->name('articles.show');
            Route::post('store', [CMSArticleController::class, 'store'])->name('articles.store');
            Route::post('update/{id}', [CMSArticleController::class, 'update'])->name('articles.update');
            Route::delete('delete/{id}', [CMSArticleController::class, 'destroy'])->name('articles.destroy');
            Route::post('upload/image', [CMSArticleController::class, 'upload_image'])->name('articles.upload_image');
            Route::post('related/add', [RelatedArticleController::class, 'store'])->name('related.articles.add');
            Route::delete('related/remove/{relevant}', [RelatedArticleController::class, 'destroy'])->name('related.articles.remove');

            Route::prefix('sub_article')->group(function() {
                Route::get('show/{id}', [SubArticleContentController::class, 'show'])->name('contents.show');
                Route::post('store', [SubArticleContentController::class, 'store'])->name('contents.store');
                Route::post('update/{id}', [SubArticleContentController::class, 'update'])->name('contents.update');
                Route::delete('delete/{id}', [SubArticleContentController::class, 'destroy'])->name('contents.destroy');
            });
        });

        Route::prefix('categories')->group(function() {
            Route::get('', [CategoryController::class, 'index'])->name('categories.index');
            Route::get('show/{id}', [CategoryController::class, 'show'])->name('categories.show');
            Route::post('store', [CategoryController::class, 'store'])->name('categories.store');
            Route::post('update/{id}', [CategoryController::class, 'update'])->name('categories.update');
            Route::delete('delete/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        });

        Route::prefix('albums')->group(function() {
            Route::get('', [AlbumController::class, 'index'])->name('albums.index');
            Route::get('show/{id}', [AlbumController::class, 'show'])->name('albums.show');
            Route::post('store', [AlbumController::class, 'store'])->name('albums.store');
            Route::post('update/{id}', [AlbumController::class, 'update'])->name('albums.update');
            Route::delete('delete/{id}', [AlbumController::class, 'destroy'])->name('albums.destroy');
            Route::post('upload/image', [AlbumController::class, 'upload_image'])->name('albums.upload_image');
        });

        Route::prefix('artists')->group(function() {
            Route::get('', [ArtistController::class, 'index'])->name('artists.index');
            Route::get('show/{id}', [ArtistController::class, 'show'])->name('artists.show');
            Route::post('store', [ArtistController::class, 'store'])->name('artists.store');
            Route::post('update/{id}', [ArtistController::class, 'update'])->name('artists.update');
            Route::delete('delete/{id}', [ArtistController::class, 'destroy'])->name('artists.destroy');
            Route::post('upload/image', [ArtistController::class, 'upload_image'])->name('artists.upload_image');
        });

        Route::prefix('charts')->group(function() {
            Route::get('', [CMSChartController::class, 'index'])->name('charts.index');
            Route::get('show/{id}', [CMSChartController::class, 'show'])->name('charts.show');
            Route::post('store', [CMSChartController::class, 'store'])->name('charts.store');
            Route::post('update/{id}', [CMSChartController::class, 'update'])->name('charts.update');
            Route::delete('delete/{id}', [CMSChartController::class, 'destroy'])->name('charts.destroy');
            Route::get('switch', [CMSChartController::class, 'switch_charts'])->name('chart.switch');
        });

        Route::prefix('genres')->group(function() {
            Route::get('', [GenreController::class, 'index'])->name('genres.index');
            Route::get('show/{id}', [GenreController::class, 'show'])->name('genres.show');
            Route::post('store', [GenreController::class, 'store'])->name('genres.store');
            Route::post('update/{id}', [GenreController::class, 'update'])->name('genres.update');
            Route::delete('delete/{id}', [GenreController::class, 'destroy'])->name('genres.destroy');
        });

        Route::prefix('outbreaks')->group(function() {
            Route::get('', [OutbreakController::class, 'index'])->name('outbreaks.index');
            Route::get('show/{id}', [OutbreakController::class, 'show'])->name('outbreaks.show');
            Route::post('store', [OutbreakController::class, 'store'])->name('outbreaks.store');
            Route::post('update/{id}', [OutbreakController::class, 'update'])->name('outbreaks.update');
            Route::delete('delete/{id}', [OutbreakController::class, 'destroy'])->name('outbreaks.destroy');
        });

        Route::prefix('songs')->group(function() {
            Route::get('', [SongController::class, 'index'])->name('songs.index');
            Route::get('show/{id}', [SongController::class, 'show'])->name('songs.show');
            Route::post('store', [SongController::class, 'store'])->name('songs.store');
            Route::post('update/{id}', [SongController::class, 'update'])->name('songs.update');
            Route::delete('delete/{id}', [SongController::class, 'destroy'])->name('songs.destroy');
            Route::post('upload/song', [SongController::class, 'upload_file'])->name('songs.upload_file');
        });

        Route::prefix('southsides')->group(function() {
            Route::get('', [SouthsideController::class, 'index'])->name('southsides.index');
        });

        Route::prefix('headers')->group(function() {
            Route::get('', [HeaderController::class, 'index'])->name('headers.index');
            Route::get('show/{id}', [HeaderController::class, 'show'])->name('headers.show');
            Route::post('store', [HeaderController::class, 'store'])->name('headers.store');
            Route::post('update/{id}', [HeaderController::class, 'update'])->name('headers.update');
            Route::delete('delete/{id}', [HeaderController::class, 'destroy'])->name('headers.destroy');
            Route::post('upload/image', [HeaderController::class, 'upload_image'])->name('headers.upload_image');
        });

        Route::prefix('photos')->group(function() {
            Route::post('upload/image', [PhotoController::class, 'upload_image'])->name('photos.upload_image');
        });

        Route::prefix('wallpapers')->group(function() {
            Route::get('', [WallpaperController::class, 'index'])->name('wallpapers.index');
            Route::get('show/{id}', [WallpaperController::class, 'show'])->name('wallpapers.show');
            Route::post('store', [WallpaperController::class, 'store'])->name('wallpapers.store');
            Route::post('update/{id}', [WallpaperController::class, 'update'])->name('wallpapers.update');
            Route::delete('delete/{id}', [WallpaperController::class, 'destroy'])->name('wallpapers.destroy');
            Route::post('upload/image', [WallpaperController::class, 'upload_image'])->name('wallpapers.upload_image');
        });

        Route::prefix('scholar_batch')->group(function() {
            Route::get('', [ScholarBatchController::class, 'index'])->name('scholar.batch.index');
            Route::get('show/{id}', [ScholarBatchController::class, 'show'])->name('scholar.batch.show');
            Route::post('store', [ScholarBatchController::class, 'store'])->name('scholar.batch.store');
            Route::post('update/{id}', [ScholarBatchController::class, 'update'])->name('scholar.batch.update');
            Route::delete('delete/{id}', [ScholarBatchController::class, 'destroy'])->name('scholar.batch.destroy');
        });

        Route::prefix('sponsors')->group(function() {
            Route::get('', [SponsorController::class, 'index'])->name('sponsors.index');
            Route::get('show/{id}', [SponsorController::class, 'show'])->name('sponsors.show');
            Route::post('store', [SponsorController::class, 'store'])->name('sponsors.store');
            Route::post('update/{id}', [SponsorController::class, 'update'])->name('sponsors.update');
            Route::delete('delete/{id}', [SponsorController::class, 'destroy'])->name('sponsors.destroy');
        });

        Route::prefix('students')->group(function() {
            Route::get('', [StudentController::class, 'index'])->name('students.index');
            Route::get('show/{id}', [StudentController::class, 'show'])->name('students.show');
            Route::post('store', [StudentController::class, 'store'])->name('students.store');
            Route::post('update/{id}', [StudentController::class, 'update'])->name('students.update');
            Route::delete('delete/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
            Route::post('upload/image', [StudentController::class, 'upload_image'])->name('students.upload_image');
        });

        Route::prefix('events')->group(function() {
            Route::get('', [GimikboardController::class, 'index'])->name('events.index');
            Route::get('show/{id}', [GimikboardController::class, 'show'])->name('events.show');
            Route::post('store', [GimikboardController::class, 'store'])->name('events.store');
            Route::post('update/{id}', [GimikboardController::class, 'update'])->name('events.update');
            Route::delete('delete/{id}', [GimikboardController::class, 'destroy'])->name('events.destroy');
            Route::post('upload/image', [GimikboardController::class, 'upload_image'])->name('events.upload_image');
        });

        Route::prefix('schools')->group(function() {
            Route::get('', [SchoolController::class, 'index'])->name('schools.index');
            Route::get('show/{id}', [SchoolController::class, 'show'])->name('schools.show');
            Route::post('store', [SchoolController::class, 'store'])->name('schools.store');
            Route::post('update/{id}', [SchoolController::class, 'update'])->name('schools.update');
            Route::delete('delete/{id}', [SchoolController::class, 'destroy'])->name('schools.destroy');
            Route::post('upload/image', [SchoolController::class, 'upload_image'])->name('schools.upload_image');
        });

        Route::prefix('indiegrounds')->group(function() {
            Route::get('', [IndiegroundController::class, 'index'])->name('indiegrounds.index');
            Route::get('show/{id}', [IndiegroundController::class, 'show'])->name('indiegrounds.show');
            Route::post('store', [IndiegroundController::class, 'store'])->name('indiegrounds.store');
            Route::post('update/{id}', [IndiegroundController::class, 'update'])->name('indiegrounds.update');
            Route::delete('delete/{id}', [IndiegroundController::class, 'destroy'])->name('indiegrounds.destroy');
        });

        Route::prefix('featured_indiegrounds')->group(function() {
            Route::get('', [FeaturedArtistController::class, 'index'])->name('featured.indiegrounds.index');
            Route::get('show/{id}', [FeaturedArtistController::class, 'show'])->name('featured.indiegrounds.show');
            Route::post('store', [FeaturedArtistController::class, 'store'])->name('featured.indiegrounds.store');
            Route::post('update/{id}', [FeaturedArtistController::class, 'update'])->name('featured.indiegrounds.update');
            Route::delete('delete/{id}', [FeaturedArtistController::class, 'destroy'])->name('featured.indiegrounds.destroy');
        });

        Route::prefix('jocks')->group(function() {
            Route::get('', [CMSJockController::class, 'index'])->name('jocks.index');
            Route::get('show/{id}', [CMSJockController::class, 'show'])->name('jocks.show');
            Route::post('store', [CMSJockController::class, 'store'])->name('jocks.store');
            Route::post('update/{id}', [CMSJockController::class, 'update'])->name('jocks.update');
            Route::delete('delete/{id}', [CMSJockController::class, 'destroy'])->name('jocks.destroy');
            Route::post('upload/image', [CMSJockController::class, 'upload_image'])->name('jocks.upload_image');
        });

        Route::prefix('podcasts')->group(function() {
            Route::get('', [CMSPodcastController::class, 'index'])->name('podcasts.index');
            Route::get('show/{id}', [CMSPodcastController::class, 'show'])->name('podcasts.show');
            Route::post('store', [CMSPodcastController::class, 'store'])->name('podcasts.store');
            Route::post('update/{id}', [CMSPodcastController::class, 'update'])->name('podcasts.update');
            Route::delete('delete/{id}', [CMSPodcastController::class, 'destroy'])->name('podcasts.destroy');
            Route::post('upload/image', [CMSPodcastController::class, 'upload_image'])->name('podcasts.upload_image');
        });

        Route::prefix('shows')->group(function() {
            Route::get('', [CMSShowController::class, 'index'])->name('shows.index');
            Route::get('show/{id}', [CMSShowController::class, 'show'])->name('shows.show');
            Route::post('store', [CMSShowController::class, 'store'])->name('shows.store');
            Route::post('update/{id}', [CMSShowController::class, 'update'])->name('shows.update');
            Route::delete('delete/{id}', [CMSShowController::class, 'destroy'])->name('shows.destroy');
            Route::post('upload/image', [CMSShowController::class, 'upload_image'])->name('shows.upload_image');
        });

        Route::prefix('timeslots')->group(function() {
            Route::get('', [TimeslotController::class, 'index'])->name('timeslots..index');
            Route::get('show/{id}', [TimeslotController::class, 'show'])->name('timeslots..show');
            Route::post('store', [TimeslotController::class, 'store'])->name('timeslots..store');
            Route::post('update/{id}', [TimeslotController::class, 'update'])->name('timeslots..update');
            Route::delete('delete/{id}', [TimeslotController::class, 'destroy'])->name('timeslots..destroy');
        });

        Route::prefix('promo')->group(function() {
            Route::prefix('contestants')->group(function() {
                Route::get('', [ContestantController::class, 'index'])->name('contestants.index');
                Route::get('show/{id}', [ContestantController::class, 'show'])->name('contestants.show');
                Route::post('store', [ContestantController::class, 'store'])->name('contestants.store');
                Route::post('update/{id}', [ContestantController::class, 'update'])->name('contestants.update');
                Route::delete('delete/{id}', [ContestantController::class, 'destroy'])->name('contestants.destroy');
                Route::post('upload/image', [ContestantController::class, 'upload_image'])->name('contestants.upload_image');
            });

            Route::prefix('giveaways')->group(function() {
                Route::get('', [GiveawayController::class, 'index'])->name('giveaways..index');
                Route::get('show/{id}', [GiveawayController::class, 'show'])->name('giveaways..show');
                Route::post('store', [GiveawayController::class, 'store'])->name('giveaways..store');
                Route::post('update/{id}', [GiveawayController::class, 'update'])->name('giveaways..update');
                Route::delete('delete/{id}', [GiveawayController::class, 'destroy'])->name('giveaways..destroy');
                Route::post('upload/image', [GiveawayController::class, 'upload_image'])->name('giveaways.upload_image');
            });
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

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
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
