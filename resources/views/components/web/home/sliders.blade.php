<div id="mainCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach($sliders as $index => $slider)
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 }}" aria-label="{{ $slider->title }}"></button>
        @endforeach
    </div>
    <div class="carousel-inner">
        @foreach($sliders as $index => $slider)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" data-bs-interval="2000">
                @if(!$slider->link)
                    <img src="{{ $slider->image }}" alt="{{ $slider->title }}">
                @else
                    <img src="{{ $slider->image }}" alt="{{ $slider->title }}" onclick="window.open({{ $slider->link }})">
                @endif
            </div>
        @endforeach
    </div>
</div>

<style>
    .carousel-item > img {
        width: 100%;
    }
</style>
