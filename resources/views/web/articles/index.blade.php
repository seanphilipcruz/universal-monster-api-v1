@extends('layouts.app')

@include('components.web.meta', [

])

@section('content')
    @include('components.web.banner', [
        'image' => ''
    ])
@endsection
