<div class="bg-medium-gray-container">
    <div class="container">
        <div class="my-5">
            <div class="card bg-medium-gray border-transparent">
                <div class="my-4">
                    <div class="d-block d-sm-block d-md-none d-lg-none d-xl-none d-xxl-none">
                        <div class="row g-0">
                            <div class="col-2 text-center">
                                <div class="my-1"></div>
                                <img src="{{ request()->url() . '/images/home/latest-news.png' }}" alt="latest-news" class="img-fluid img-icon">
                            </div>
                            <div class="col-10">
                                <p class="fs-4 header mb-0">WHAT'S HOT</p>
                                <p class="barlow text-light">Articles, Podcasts, and YouTube Videos</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-none d-md-block d-lg-block d-xl-block d-xxl-block">
                        <div class="row g-0">
                            <div class="col-1">
                                <img src="{{ request()->url() . '/images/home/latest-news.png' }}" alt="latest-news" class="img-fluid img-icon">
                            </div>
                            <div class="col-11">
                                <p class="fs-4 header mb-0">WHAT'S HOT</p>
                                <p class="barlow text-light">Articles, Podcasts, and YouTube Videos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SMALL TO MEDIUM DEVICES -->
            <div class="d-block d-lg-none d-xl-none d-xxl-none">
                <div class="row g-3 justify-content-center">
                    @if($article !== null)
                        <div class="col mb-4">
                            <div class="card bg-light-gray text-light">
                                <img src="{{ $article->image }}" alt="latest-article" class="card-img-top rounded-start">
                                <div class="card-body">
                                    <p class="mb-0 text-center card-title">News, Blogs, and Articles</p>
                                </div>
                            </div>
                            <div class="my-2"></div>
                            <a href="#" class="btn btn-info btn-monster-blue d-grid btn-rounded">VIEW ARTICLE</a>
                        </div>
                    @endif
                    @if($tmr !== null)
                        <div class="col mb-4">
                            <div class="card bg-light-gray text-light">
                                <img src="{{ $tmr->image }}" alt="tmr" class="card-img-top rounded-start">
                                <div class="card-body">
                                    <p class="mb-0 text-center card-title">The Morning Rush</p>
                                </div>
                            </div>
                            <div class="my-2"></div>
                            <a href="#" class="btn btn-info btn-monster-blue d-grid btn-rounded">LISTEN MORE</a>
                        </div>
                    @endif
                    @if($podcast !== null)
                        <div class="col mb-4">
                            <div class="card bg-light-gray text-light">
                                <img src="{{ $podcast->image }}" alt="latest-podcast" class="card-img-top rounded-start">
                                <div class="card-body">
                                    <p class="mb-0 text-center card-title">{{ $stationName }} Podcast</p>
                                </div>
                            </div>
                            <div class="my-2"></div>
                            <a href="#" class="btn btn-info btn-monster-blue d-grid btn-rounded">LISTEN MORE</a>
                        </div>
                    @endif
                    <div class="col mb-4">
                        <div class="card bg-light-gray text-light">
                            <img src="{{ request()->url() . '/images/home/thumbnail.jpg' }}" alt="youtube-thumbnail" class="card-img-top rounded-start">
                            <div class="card-body">
                                <p class="mb-0 text-center card-title">YouTube Channel</p>
                            </div>
                        </div>
                        <div class="my-2"></div>
                        <a href="https://youtube.com/user/RX931" target="_blank" class="btn btn-info btn-monster-blue d-grid btn-rounded">WATCH AND SUBSCRIBE</a>
                    </div>
                </div>
            </div>

            <!-- LARGE TO EXTRA LARGE DEVICES -->
            <div class="d-none d-lg-block d-xl-block d-xxl-block">
                <div class="row g-0 justify-content-center">
                    <div class="col-12">
                        <div class="row justify-content-center">
                            @if($article !== null)
                                <div class="col col-sm-6 col-md-6 col-lg-3 col-xl-3 col-xxl-3 mt-4">
                                    <div class="card bg-light-gray text-light">
                                        <div class="image-container">
                                            <img src="{{ $article->image }}" alt="latest-article" class="card-img-top rounded-start">
                                            <div class="overlay">
                                                <img src="{{ request()->url() . '/images/home/articles.png' }}" alt="latest-image" class="icon">
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="mb-0 text-center card-title">News, Blogs, and Articles</p>
                                        </div>
                                    </div>
                                    <div class="my-2"></div>
                                    <a href="#" class="btn btn-info btn-monster-blue d-grid btn-rounded">VIEW ARTICLE</a>
                                </div>
                            @endif
                            @if($tmr !== null)
                                <div class="col col-sm-6 col-md-6 col-lg-3 col-xl-3 col-xxl-3 mt-4">
                                    <div class="card bg-light-gray text-light">
                                        <div class="image-container">
                                            <img src="{{ $tmr->image }}" alt="tmr" class="card-img-top rounded-start">
                                            <div class="overlay">
                                                <img src="{{ request()->url() . '/images/home/tmr.png' }}" alt="tmr" class="icon">
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="mb-0 text-center card-title">The Morning Rush</p>
                                        </div>
                                    </div>
                                    <div class="my-2"></div>
                                    <a href="#" class="btn btn-info btn-monster-blue d-grid btn-rounded">LISTEN MORE</a>
                                </div>
                            @endif
                            @if($podcast !== null)
                                <div class="col col-sm-6 col-md-6 col-lg-3 col-xl-3 col-xxl-3 mt-4">
                                    <div class="card bg-light-gray text-light">
                                        <div class="image-container">
                                            <img src="{{ $podcast->image }}" alt="latest-podcast" class="card-img-top rounded-start">
                                            <div class="overlay">
                                                <img src="{{ request()->url() . '/images/home/podcast.png' }}" alt="latest-podcast" class="icon">
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="mb-0 text-center card-title">{{ $stationName }} Podcast</p>
                                        </div>
                                    </div>
                                    <div class="my-2"></div>
                                    <a href="#" class="btn btn-info btn-monster-blue d-grid btn-rounded">LISTEN MORE</a>
                                </div>
                            @endif
                            <div class="col col-sm-6 col-md-6 col-lg-3 col-xl-3 col-xxl-3 mt-4">
                                <div class="card bg-light-gray text-light">
                                    <div class="image-container">
                                        <img src="{{ request()->url() . '/images/home/thumbnail.jpg' }}" alt="latest-youtube" class="card-img-top rounded-start">
                                        <div class="overlay">
                                            <img src="{{ request()->url() . '/images/home/youtube.png' }}" alt="latest-youtube" class="icon">
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0 text-center card-title">YouTube Channel</p>
                                    </div>
                                </div>
                                <div class="my-2"></div>
                                <a href="https://youtube.com/user/RX931" target="_blank" class="btn btn-info btn-monster-blue d-grid btn-rounded">WATCH AND SUBSCRIBE</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
