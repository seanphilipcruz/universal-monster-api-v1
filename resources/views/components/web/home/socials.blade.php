<div class="green-container" style='background-image: url(/images/home/socials-banner.jpg)'>
    <div class="container">
        <div class="my-5">
            <div class="row">
                <div class="col-12">
                    <!-- SMALL DEVICES -->
                    <div class="d-block d-md-block d-lg-none d-xl-none d-xxl-none">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card bg-transparent border-transparent">
                                    <div class="d-block d-sm-block d-md-block d-lg-none d-xl-none d-xxl-none">
                                        <div class="row justify-content-center">
                                            <div class="col-3 text-center">
                                                <div class="my-1"></div>
                                                <img src="{{ request()->url() . '/images/home/socials.png' }}"
                                                     alt="socials" class="img-fluid img-icon">
                                            </div>
                                            <div class="col">
                                                <div class="mx-2">
                                                    <p class="fs-4 header-medium-gray mb-0">GET CONNECTED</p>
                                                    <p class="barlow text-medium-gray">Follow {{ $stationName }} on
                                                        social media</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @include('components.web.home.social_media.buttons')
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-7">
                                <div class="my-3"></div>
                                @include('components.web.home.social_media.view', ['type' => 'home'])
                            </div>
                        </div>
                    </div>

                    <!-- MEDIUM TO EXTRA LARGE DEVICES -->
                    <div class="d-none d-sm-none d-md-none d-lg-block d-xl-block d-xxl-block">
                        <div class="row g-0 justify-content-center">
                            <div class="col-5">
                                <div class="my-4"></div>
                                <div class="row g-0 justify-content-center">
                                    <div class="col-2 text-center">
                                        <img src="{{ request()->url() . '/images/home/socials.png' }}" alt="socials"
                                             class="img-fluid img-icon">
                                    </div>
                                    <div class="col-7">
                                        <p class="fs-4 header-medium-gray mb-0">GET CONNECTED</p>
                                        <p class="barlow text-medium-gray">Follow {{ $stationName }} on social media</p>
                                    </div>
                                </div>
                                <div class="my-4"></div>
                                @include('components.web.home.social_media.buttons')
                            </div>
                            <div class="col-5">
                                <div class="my-3"></div>
                                @include('components.web.home.social_media.view', ['type' => 'home'])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
