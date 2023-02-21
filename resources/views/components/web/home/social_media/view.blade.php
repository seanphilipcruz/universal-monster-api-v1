<div class="socials-container">
    <div class="facebook active text-center">
        @if($type === 'home')
            <iframe
                    src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Frx931%2F&tabs=timeline&width=400&height=541&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=250218719159269"
                    width="400"
                    height="541"
                    style="border:none;overflow:hidden"
                    scrolling="no"
                    frameborder="0"
                    allowfullscreen="true"
                    allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
            </iframe>
        @else
            <iframe
                    src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Frxradio1%2F&tabs=timeline&width=400&height=541&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=250218719159269"
                    width="400"
                    height="541"
                    style="border:none;overflow:hidden"
                    scrolling="no"
                    frameborder="0"
                    allowfullscreen="true"
                    allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
            </iframe>
        @endif
    </div>
    <div class="twitter text-center" hidden>
        @if($type === 'radio1')
            <a
                class="twitter-timeline"
                data-width="400"
                data-height="541"
                data-dnt="true"
                data-theme="dark"
                href="https://twitter.com/RXRadio1?ref_src=twsrc%5Etfw">
                Tweets by RXRadio1
            </a>
        @else
            <a
                class="twitter-timeline"
                data-width="400"
                data-height="541"
                data-dnt="true"
                data-theme="dark"
                href="https://twitter.com/RX931?ref_src=twsrc%5Etfw">
                Tweets by RX931
            </a>
        @endif
    </div>
    <div class="instagram text-center" hidden>
        @if($type === 'radio1')
            <iframe
                src="https://snapwidget.com/embed/952318"
                class="snapwidget-widget"
                allowtransparency="true"
                frameborder="0"
                scrolling="no"
                style="border:none; overflow:hidden;  width:100%; ">
            </iframe>
        @else
            <iframe
                src="https://snapwidget.com/embed/780341"
                class="snapwidget-widget"
                allowtransparency="true"
                frameborder="0"
                scrolling="no"
                style="border:none; overflow:hidden;  width:100%; ">
            </iframe>
        @endif
    </div>
</div>
