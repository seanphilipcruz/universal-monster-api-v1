<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, viewport-fit=cover, initial-scale=1.0">
    <title>Monster RX93.1</title>
    <meta name="robots" content="index">
    <meta name="subject" content="radio">
    <meta name="language" content="ES">
    <meta name="author" content="Audiovisual Communicators Inc.">
    <meta name="url" content="https://rx931.com">
    <meta name="DC.title" content="monsterrx931">
    <meta name="geo.region" content="PH">
    <meta name="geo.placename" content="Pasig City">
    <meta name="geo.position" content="14.5870716; 121.0610674">
    <meta name="ICBM" content="14.5870716, 121.0610674">
    <meta name="format-detection" content="telephone=no">
    <meta name="subject" content="radio">
    <meta http-equiv="Content-Security-Policy"
          content="img-src * 'self' data:;
          default-src * 'self' gap: wss: ws: ;
          style-src * 'self' 'unsafe-inline' 'unsafe-eval';
          script-src * 'self' 'unsafe-inline' 'unsafe-eval';" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@RX931" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/bootstrap-social.css') }}" rel="stylesheet" type="text/css">
    <script src="https://platform.twitter.com/widgets.js" crossorigin="anonymous"></script>
    <script src="https://snapwidget.com/js/snapwidget.js" crossorigin="anonymous"></script>
</head>
<body class="bg-dark-gray">
    <div id="app">
        @include('components.web.navigation', [
            'stationChart' => env('APP_CODE') === 'mnl' ? 'Countdown Top 7' :
                env('APP_CODE') === 'cbu' ? 'Monster Hot 40' : 'Monster\'s Top 30',
            'stationName' => env('APP_CODE') === 'mnl' ? 'Monster RX93.1' :
                env('APP_CODE') === 'cbu' ? 'Monster BT105.9 Cebu' : 'Monster BT99.5 Davao',
            'stationCode' => env('APP_CODE')
        ])
        @yield('content')
        @include('components.web.footer', [
            'version' => env('APP_CODE') === 'mnl' ? 2.2 :
                env('APP_CODE') === 'cbu' ? 2.2 : 2.1
        ])
    </div>
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/all.min.js') }}"></script>
    @include('components.web.scripts')
</body>
</html>
