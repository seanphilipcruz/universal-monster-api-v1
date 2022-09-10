<div class="section">
	<?php $count = 1;?>
	<div class="row">
		<h4 class="center">Monster Jocks</h4>
		@forelse($jock as $jocks)
            @if($count%2 == 1)
              <div class="row">
            @endif
			<div class="col s6 m6 l6">
				<div class="card small">
						<div class="card-image">
							<img src="http://rx931.com/images/articles/{{ $jocks->profile_image }}">
							<span class="card-title"><strong>{{ $jocks->name }}</strong></span>
						</div>
					<div class="card-content">
						@forelse($jocks->shows as $shows)
							<p>{{ $shows->title }}</p>
						@empty
						@endforelse
					</div>
				</div>
			</div>
            @if($count%4 == 0)
              </div>
              <div class="divider"></div>
            @endif
            <?php $count++; ?>
		@empty
		@endforelse
        @if($count%2 != 1)
          </div>
          <div class="divider"></div>
        @endif
	</div>
</div>
