<?php if($contents): ?>
    <?php foreach($contents as $c): ?>
        <?php $c = $c['ProjectContent'] ?>

        <?php if($c['open'] == 1): ?>
            <?php if($c['type'] == 'text'): ?>
                <div class="text_content">
                    <?php echo nl2br($c['txt_content']) ?>
                </div>

            <?php elseif($c['type'] == 'img'): ?>
                <div class="img_content">
                    <?php echo $this->Label->image($c['img'], array('class' => 'img-responsive')) ?>
                    <?php echo h($c['img_caption']) ?>
                </div>

            <?php else: ?>
                <div class="movie_content">
                    <?php if($c['movie_type'] == 'youtube'): ?>
                        <iframe class="movie" src="//www.youtube.com/embed/<?php echo h($c['movie_code']) ?>"
                                frameborder="0" allowfullscreen></iframe>
                    <?php else: ?>
                        <iframe class="movie"
                                src="//player.vimeo.com/video/<?php echo h($c['movie_code']) ?>?title=0&amp;byline=0&amp;portrait=0&amp;color=9c1e1e"
                                frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif ?>
    <?php endforeach; ?>
<?php endif; ?>
