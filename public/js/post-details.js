(function($) {
    $(function() {
        var jcarousel = $('.jcarousel');

        jcarousel
            .on('jcarousel:reload jcarousel:create', function () {
                var carousel = $(this),
                    width = carousel.innerWidth();

                if (width >= 600) {
                    width = width / 2;
                } else if (width >= 350) {
                    width = width / 1;
                }

                carousel.jcarousel('items').css('width', Math.ceil(width) + 'px');
            })
            .jcarousel({
                wrap: 'circular'
            });

        $('.jcarousel-control-prev')
            .jcarouselControl({
                target: '-=1'
            });

        $('.jcarousel-control-next')
            .jcarouselControl({
                target: '+=1'
            });

        $('.jcarousel-pagination')
            .on('jcarouselpagination:active', 'a', function() {
                $(this).addClass('active');
            })
            .on('jcarouselpagination:inactive', 'a', function() {
                $(this).removeClass('active');
            })
            .on('click', function(e) {
                e.preventDefault();
            })
            .jcarouselPagination({
                perPage: 1,
                item: function(page) {
                    return '<a href="#' + page + '">' + page + '</a>';
                }
            });
    });
})(jQuery);

/*STRAT: Instagram Feed*/

$.ajax({
    url: "https://instagram.com/_stickermaker/?__a=1",
    type: 'GET',
    dataType: 'json', // added data type
    success: function(response) {
        processInstagramResponse(response);
    }
});

function processInstagramResponse(response){
    var user = response.graphql.user;
    console.log(user);
    $('.follow-on-instagram img.logo').attr('src', user.profile_pic_url);
    $('.follow-on-instagram #post_count').text(user.edge_owner_to_timeline_media.count);
    $('.follow-on-instagram #followers_count').text(user.edge_followed_by.count);
    $('.follow-on-instagram #following_count').text(user.edge_follow.count);

    var postHtml = '';
    user.edge_owner_to_timeline_media.edges.forEach(function (item, index){
        if(index > 5){
            return true;
        }
        var node = item.node;
        if(index == 0 || index % 2 == 0){
            postHtml += '<div class="row">';
        }

        postHtml += '<div class="col-sm-6 mt-4">';
        postHtml += '<a target="_blank" href="https://www.instagram.com/p/'+node.shortcode+'/">';
        postHtml += '<div class="post-body">';
        postHtml += '<img class="embed-responsive border instagram-post-image" src="'+node.display_url+'" alt=""/>';
        if(node.__typename == 'GraphVideo'){
            postHtml += '<i class="fas fa-video"></i>';
        }
        postHtml += '<div class="hover-info">';
        postHtml += '<div class="content">';
        if(node.is_video){
            postHtml += '<i class="fas fa-play"></i>';
            postHtml +=  node.video_view_count;
        }else{
            postHtml += '<i class="fas fa-heart"></i>';
            postHtml += node.edge_media_preview_like.count;
        }
        postHtml += '&nbsp;&nbsp;&nbsp;';
        postHtml += '<i class="fas fa-comment"></i>';
        postHtml += node.edge_media_to_comment.count;
        postHtml += '</div>';
        postHtml += '</div>';
        postHtml += '</div>';
        postHtml += '</a>';
        postHtml += '</div>';
        if(index % 2 == 1 || index >= (user.edge_owner_to_timeline_media.edges - 1) ){
            postHtml += '</div>';
        }
    });
    $('.posts').html(postHtml);
    $('.follow-on-instagram').removeClass('d-none');
}
/*END: Instagram Feed*/

/**
 *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
 *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables    */
/*
var disqus_config = function () {
this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
};
*/
(function() { // DON'T EDIT BELOW THIS LINE
    var d = document, s = d.createElement('script');
    s.src = 'https://stickermaker.disqus.com/embed.js';
    s.setAttribute('data-timestamp', +new Date());
    (d.head || d.body).appendChild(s);
})();
