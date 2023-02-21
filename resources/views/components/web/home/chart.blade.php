<div class="container">
    <div class="mb-5">
        <div class="card bg-dark-gray border-transparent">
            <div class="my-5">
                <div class="d-block d-sm-block d-md-block d-lg-none d-xl-none d-xxl-none">
                    <div class="row g-0">
                        <div class="col-2 text-center">
                            <div class="my-1"></div>
                            <img src="{{ request()->url() . '/images/home/hitlist.png' }}" alt="hitlist" class="img-fluid img-icon">
                        </div>
                        <div class="col-10">
                            <div class="mx-2">
                                <p class="fs-4 header mb-0 text-uppercase">{{ $stationChart }}</p>
                                <p class="barlow text-light">Catch the countdown every Friday, 6 to 7PM with Hazel Hottie</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-none d-md-none d-lg-block d-xl-block d-xxl-block">
                    <div class="row g-0">
                        <div class="col-1">
                            <img src="{{ request()->url() . '/images/home/hitlist.png' }}" alt="hitlist" class="img-fluid img-icon">
                        </div>
                        <div class="col-11">
                            <p class="fs-4 header mb-0 text-uppercase">{{ $stationChart }}</p>
                            <p class="barlow text-light">Catch the countdown every Friday, 6 to 7PM with Hazel Hottie</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-2"></div>
        <p class="my-0 text-center text-monster-blue">MONSTER HIT OF THE WEEK <span class="text-uppercase">{{ $chartDate }}</span> RESULTS</p>
        <div class="my-4"></div>

        <!-- SMALL TO MEDIUM DEVICES -->
        <div class="d-block d-sm-block d-md-block d-lg-none d-xl-none d-xxl-none">
            <div class="row g-2">
                <div class="col-12 col-sm-12">
                    @foreach($charts as $index => $chart)
                        @if($index === 0)
                            <div class="card bg-light-gray">
                                <div class="row g-0">
                                    <div class="col-6">
                                        <img src="{{ $chart->song->album->image }}" alt="{{ $chart->song->album->name }}"  class="img-fluid rounded-start bg-medium-gray">
                                    </div>
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="card-body text-light text-wrap">
                                            <p class="my-0 font-responsive">{{ $chart->song->name }}</p>
                                            <small class="barlow-subtitle">{{ $chart->song->album->artist->name }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    <div class="d-none d-md-none d-lg-none d-xl-none d-xxl-none">
                        <div class="my-3"></div>
                        <div class="row">
                            <div class="col">
                                <a href="#" class="btn btn-info btn-monster-blue btn-rounded d-grid">LISTEN: TOP {{ $chartCount }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach($charts as $index => $chart)
                    <div class="col-12 col-sm-12">
                        @if($index > 0)
                            @if($index % 2 === 0)
                                <div class="card bg-medium-gray text-light">
                                    <div class="row g-0">
                                        <div class="col-1 d-flex justify-content-center align-self-center">{{ $chart->position }}</div>
                                        <div class="col-3">
                                            <img src="{{ $chart->song->album->image }}" alt="{{ $chart->song->album->name }}" class="img-fluid rounded-start bg-light-gray">
                                        </div>
                                        <div class="col">
                                            <div class="card-body card-body-chart text-light text-wrap">
                                                <p class="my-0 font-responsive">{{ $chart->song->name }}</p>
                                                <small class="barlow-subtitle">{{ $chart->song->album->artist->name }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="card bg-light-gray text-light">
                                    <div class="row g-0">
                                        <div class="col-1 d-flex justify-content-center align-self-center">{{ $chart->position }}</div>
                                        <div class="col-3">
                                            <img src="{{ $chart->song->album->image }}" alt="{{ $chart->song->album->name }}" class="img-fluid rounded-start bg-medium-gray">
                                        </div>
                                        <div class="col">
                                            <div class="card-body card-body-chart text-light text-wrap">
                                                <p class="my-0 font-responsive">{{ $chart->song->name }}</p>
                                                <small class="barlow-subtitle">{{ $chart->song->album->artist->name }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        <div class="my-3"></div>
                        <div class="d-block d-sm-block d-md-none d-lg-none d-xl-none d-xxl-none">
                            <div class="row">
                                <div class="col">
                                    <a href="#" class="btn btn-info btn-monster-blue btn-rounded d-grid">LISTEN: TOP {{ $chartCount }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="my-2"></div>
            <div class="d-none d-md-block d-lg-none d-xl-none d-xxl-none">
                <div class="row justify-content-center">
                    <div class="col-8">
                        <a href="#" class="btn btn-info btn-monster-blue btn-rounded d-grid">LISTEN: TOP {{ $chartCount }}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- LARGE TO EXTRA LARGE DEVICES -->
        <div class="d-none d-sm-none d-md-none d-lg-block d-xl-block d-xxl-block">
            <div class="row justify-content-center">
                <div class="col-8">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            @foreach($charts as $index => $chart)
                                @if($index === 0)
                                    <div class="card bg-light-gray">
                                        <img src="{{ $chart->song->album->image }}" alt="{{ $chart->song->album->name }}" class="img-fluid rounded-start bg-medium-gray">
                                        <div class="card-body text-light text-wrap">
                                            <h5 class="my-0 font-responsive">{{ $chart->song->name }}</h5>
                                            <p class="barlow-subtitle">{{ $chart->song->album->artist->name }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-12">
                                    @foreach($charts as $index => $chart)
                                        @if($index > 0)
                                            @if($index % 2 === 0)
                                                <div class="card bg-medium-gray text-light">
                                                    <div class="row g-0">
                                                        <div class="col-1 d-flex justify-content-center align-self-center">{{ $chart->position }}</div>
                                                        <div class="col-2">
                                                            <img src="{{ $chart->song->album->image }}" alt="{{ $chart->song->album->name }}" class="img-fluid rounded-start bg-light-gray">
                                                        </div>
                                                        <div class="col-9">
                                                            <div class="card-body card-body-chart text-light text-wrap">
                                                                <h6 class="my-0 font-responsive" title="{{ $chart->song->name }}">{{ trimString($chart->song->name, 20) }}</h6>
                                                                <p class="barlow-subtitle">{{ $chart->song->album->artist->name }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="card bg-light-gray text-light">
                                                    <div class="row g-0">
                                                        <div class="col-1 d-flex justify-content-center align-self-center">{{ $chart->position }}</div>
                                                        <div class="col-2">
                                                            <img src="{{ $chart->song->album->image }}" alt="{{ $chart->song->album->name }}" class="img-fluid rounded-start bg-medium-gray">
                                                        </div>
                                                        <div class="col-9">
                                                            <div class="card-body card-body-chart text-light text-wrap">
                                                                <h6 class="my-0 font-responsive" title="{{ $chart->song->name }}">{{ trimString($chart->song->name, 20) }}</h6>
                                                                <p class="barlow-subtitle">{{ $chart->song->album->artist->name }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="d-none d-xl-block d-xxl-block">
                                <div class="my-2"></div>
                                <div class="row">
                                    <div class="col-12">
                                        <a href="#" class="btn btn-info btn-monster-blue btn-rounded d-grid">LISTEN: TOP {{ $chartCount }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    function trimString($string = "", $limit = 0, $end = "...") {
        if (strlen($string) > $limit) {
            return substr($string, 0, $limit) . $end;
        }

        return $string;
    }
@endphp
