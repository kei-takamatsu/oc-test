<div class="none new_item new_movie">
    <div class="form-group">
        <?php echo $this->Form->input('movie_code'.$content_no, array(
                'label' => 'YoutubeかVimeoの動画URLを入力してください', 'class' => 'form-control movie_url',
                'onchange' => 'check_movie_type($(this));'
        )); ?>
        <br>
        <?php if(!empty($this->request->data['Setting']['movie_code'.$content_no])): ?>
            <?php if($this->request->data['Setting']['movie_type'.$content_no] == 'youtube'): ?>
                <iframe class="movie"
                        src="//www.youtube.com/embed/<?php echo h($this->request->data['Setting']['movie_code'
                                                                                                  .$content_no]) ?>"
                        frameborder="0" allowfullscreen></iframe>
            <?php else: ?>
                <iframe class="movie"
                        src="//player.vimeo.com/video/<?php echo h($this->request->data['Setting']['movie_code'
                                                                                                   .$content_no]) ?>?title=0&amp;byline=0&amp;portrait=0&amp;color=9c1e1e"
                        frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php echo $this->Form->hidden('movie_type'.$content_no, array('class' => 'movie_type')) ?>
</div>