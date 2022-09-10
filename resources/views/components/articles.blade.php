<div class="section">
	<div class="row">
		<h4 class="center">News, Blogs & Articles</h4>
        <div class="col s12 m12 l6">
            <div class="card medium">
                <div class="card-image">
                    <img src="http://rx931.com/images/articles/{{ $article->image }}">
                    <span class="card-title">{{ $article->heading }}</span>
                </div>
                <div class="card-content">
                    @foreach($article->Content as $content)
                        {!! $content->content !!}
                    @endforeach
                </div>
                <div class="card-action">
                    <a href="#">Read</a>
                </div>
            </div>
        </div>
	</div>
</div>
