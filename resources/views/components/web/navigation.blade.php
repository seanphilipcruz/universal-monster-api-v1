<nav class="navbar navbar-expand-xxl navbar-dark bg-dark-gray">
    <div class="container-fluid">
        <a href="{{ route('web.home') }}" class="navbar-brand">
            <img src="{{ request()->url() . '/images/logo.png' }}" alt="{{ $stationName }}">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target=".collapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse">
            @if($stationCode === 'mnl')
                <!-- Monster RX93.1 -->
                <ul class="navbar-nav ms-auto text-center">
                    <li class="nav-item dropdown">
                        <a id="latest-dropdown" href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">WHAT'S HOT</a>

                        <ul class="dropdown-menu text-center" aria-labelledby="latest-dropdown">
                            <li><a href="#" class="dropdown-item">News/Articles</a></li>
                            <li><a href="#" class="dropdown-item">Podcasts</a></li>
                            <li><a href="#" class="dropdown-item">Monster Wallpapers</a></li>
                            <li><a href="https://www.youtube.com/user/RX931" target="_blank" class="dropdown-item">YouTube Channel</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">SHOWS</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="jocks-dropdown" href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">JOCKS</a>

                        <ul class="dropdown-menu text-center" aria-labelledby="jocks-dropdown">
                            <li><a href="#" class="dropdown-item">Monster Jocks</a></li>
                            <li><a href="#" class="dropdown-item">Radio1 Jocks</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="charts-dropdown" href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">CHARTS</a>

                        <ul class="dropdown-menu text-center" aria-labelledby="charts-dropdown">
                            <li><a href="#" class="dropdown-item">{{ $stationChart }}</a></li>
                            <li><a href="#" class="dropdown-item">The Daily Survey Top 5</a></li>
                            <li><a href="#" class="dropdown-item">Countdown Voting</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a :to="{ name: 'Indieground' }" class="nav-link text-uppercase">Indieground</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="misc-dropdown" href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">OTHERS</a>

                        <ul class="dropdown-menu text-center" aria-labelledby="misc-dropdown">
                            <li><a href="#" class="dropdown-item">Gimikboard</a></li>
                            <li><a href="#" class="dropdown-item">Monster Scholars</a></li>
                            <li><a href="#" class="dropdown-item">Contact</a></li>
                            <li><a href="#" class="dropdown-item">Privacy Policy</a></li>
                            <li><a href="#" class="dropdown-item">About RX</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="btn btn-info btn-monster-blue btn-rounded">LISTEN LIVE</a>
                    </li>
                </ul>
            @elseif($stationCode === 'cbu')
                <!-- Monster Cebu BT105.9 -->
                <ul class="navbar-nav ms-auto text-center">
                    <li class="nav-item dropdown">
                        <a id="latest-dropdown" href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">WHAT'S HOT</a>

                        <ul class="dropdown-menu text-center" aria-labelledby="latest-dropdown">
                            <li><a href="#" class="dropdown-item">News/Articles</a></li>
                            <li><a href="#" class="dropdown-item">Podcasts</a></li>
                            <li><a href="#" class="dropdown-item">Monster Wallpapers</a></li>
                            <li><a href="https://www.youtube.com/channel/UCfJcAW5qTdVgZK0AdTQW0cA" target="_blank" class="dropdown-item">YouTube Channel</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">SHOWS</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">JOCKS</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="charts-dropdown" href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">CHARTS</a>

                        <ul class="dropdown-menu text-center" aria-labelledby="charts-dropdown">
                            <li><a href="#" class="dropdown-item">{{ $stationChart }}</a></li>
                            <li><a href="#" class="dropdown-item">Southside Sounds</a></li>
                            <li><a href="#" class="dropdown-item">Monster Hit Voting</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="misc-dropdown" href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">OTHERS</a>

                        <ul class="dropdown-menu text-center" aria-labelledby="misc-dropdown">
                            <li><a href="#" class="dropdown-item">Contact</a></li>
                            <li><a href="#" class="dropdown-item">Privacy Policy</a></li>
                            <li><a href="#" class="dropdown-item">About BT</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="btn btn-info btn-monster-blue btn-rounded">LISTEN LIVE</a>
                    </li>
                </ul>
            @else
            @endif
        </div>
    </div>
</nav>
