<div class="new_item new_movie" style="width:100%;">
    <div class="form-group">
        <?php echo $this->Form->input('thumbnail_movie_code', array(
                'label' => 'YoutubeかVimeoの動画URLを入力してください', 'class' => 'form-control thumbnail_movie_code',
                'onchange' => 'check_movie_type($(this));'
        )); ?>
        <br>
    </div>
    <div class="center-block" style="max-width:700px;">
        <?php if(!empty($this->request->data['Project']['thumbnail_movie_code'])): ?>
            <?php if($this->request->data['Project']['thumbnail_movie_type'] == 'youtube'): ?>
                <iframe class="movie"
                        src="//www.youtube.com/embed/<?php echo h($this->request->data['Project']['thumbnail_movie_code']) ?>"
                        frameborder="0" allowfullscreen></iframe>
            <?php else: ?>
                <iframe class="movie"
                        src="//player.vimeo.com/video/<?php echo h($this->request->data['Project']['thumbnail_movie_code']) ?>?title=0&amp;byline=0&amp;portrait=0&amp;color=9c1e1e"
                        frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php echo $this->Form->hidden('thumbnail_movie_type', array('class' => 'movie_type')) ?>
</div>