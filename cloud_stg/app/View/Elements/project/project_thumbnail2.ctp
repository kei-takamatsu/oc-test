<div style="text-align: center;" class="thumb">
    <?php if($project['Project']['thumbnail_type'] == 1): //画像?>
        <?php if($this->Label->link($project['Project']['pic'])): //画像あり?>
            <?php echo $this->Label->image($project['Project']['pic'], array('class' => 'img-responsive')) ?>
        <?php endif; ?>

    <?php else: //動画?>
        <?php if($this->Label->link($project['Project']['pic'])): //画像あり ?>
            <?php if(!empty($project['Project']['thumbnail_movie_code'])): ?>
                <section class="thumbnail_movie" onclick="movie_start($(this));">
                    <?php echo $this->Label->image($project['Project']['pic'], array('class' => 'img-responsive')) ?>
                    <div class="movie_start_btn"></div>
                </section>
            <?php endif; ?>
        <?php else: //画像なし?>
            <?php if(!empty($project['Project']['thumbnail_movie_code'])): ?>
                <?php if($project['Project']['thumbnail_movie_type'] == 'youtube'): ?>
                    <iframe class="movie"
                            src="//www.youtube.com/embed/<?php echo h($project['Project']['thumbnail_movie_code']) ?>?autoplay=0"
                            frameborder="0" allowfullscreen></iframe>
                <?php else: ?>
                    <iframe class="movie"
                            src="//player.vimeo.com/video/<?php echo h($project['Project']['thumbnail_movie_code']) ?>?title=0&amp;byline=0&amp;portrait=0&amp;color=9c1e1e&amp;autoplay=0"
                            frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>

    <?php endif; ?>
</div>


<?php $this->start('script'); ?>
<script>
    function movie_start(t) {
        <?php if($project['Project']['thumbnail_movie_type'] == 'youtube'):?>
        t.html('<iframe class="movie" src="//www.youtube.com/embed/<?php echo h($project['Project']['thumbnail_movie_code']) ?>?autoplay=1" frameborder="0" allowfullscreen></iframe>');
        <?php else:?>
        t.html('<iframe class="movie" src="//player.vimeo.com/video/<?php echo h($project['Project']['thumbnail_movie_code'])?>?title=0&amp;byline=0&amp;portrait=0&amp;color=9c1e1e&amp;autoplay=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
        <?php endif;?>
        resize_movie(750);
    }
</script>
<?php $this->end() ?>