@extends('layouts.app')

@include('components.web.meta', [
    'title' => 'Home',
    'social_title' => 'Home | Monster RX93.1',
    'social_description' => 'Manila\'s Hottest Radio Station',
    'social_image' => '/images/_assets/thumbnails/thmbn-mnl.jpg',
    'social_url' => request()->url(),
    'site_name' => 'Home'
])

@section('content')
    @include('components.web.home.sliders', ['sliders' => $data->headers])
    @include('components.web.home.chart', [
        'charts' => $data->charts,
        'chartCount' => env('APP_CODE') === 'mnl' ? 20 : (env('APP_CODE') === 'cbu' ? 40 : 30),
        'stationChart' => $data->stationChart->title,
        'chartDate' => $data->chart_date
    ])
    @include('components.web.home.hottest', [
        'article' => $data->articles,
        'tmr' => $data->tmr,
        'podcast' => $data->podcasts,
        'stationName' => env('APP_CODE') === 'mnl' ? 'Monster RX93.1' :
                (env('APP_CODE') === 'cbu' ? 'Monster BT105.9 Cebu' : 'Monster BT99.5 Davao')
    ])
    @include('components.web.home.socials', [
        'stationName' => env('APP_CODE') === 'mnl' ? 'Monster RX93.1' :
                (env('APP_CODE') === 'cbu' ? 'Monster BT105.9 Cebu' : 'Monster BT99.5 Davao')
    ])
    @include('components.web.home.contact')
@endsection
