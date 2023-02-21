<script type="text/javascript">
    $(document).ready(function() {
        $('button[for]').on('click', function(e) {
            e.preventDefault();

            let type = $(this).attr('for');

            let facebook = $('.facebook');
            let twitter = $('.twitter');
            let instagram = $('.instagram');
            let container = $('.socials-container');

            switch (type) {
                case 'twitter':
                    container.fadeOut(1100);
                    setTimeout(() => {
                        container.children('.active').removeClass('active').attr('hidden', 'hidden');
                        twitter.addClass('active').removeAttr('hidden');
                    }, 1100);
                    container.fadeIn(1100);
                    break;
                case 'instagram':
                    container.fadeOut(1100);
                    setTimeout(() => {
                        container.children('.active').removeClass('active').attr('hidden', 'hidden');
                        instagram.addClass('active').removeAttr('hidden');
                    }, 1100);
                    container.fadeIn(1100);
                    break;
                default:
                    container.fadeOut(1100);
                    setTimeout(() => {
                        container.children('.active').removeClass('active').attr('hidden', 'hidden');
                        facebook.addClass('active').removeAttr('hidden');
                    }, 1100);
                    container.fadeIn(1100);
                    break;
            }
        });
    })
</script>
