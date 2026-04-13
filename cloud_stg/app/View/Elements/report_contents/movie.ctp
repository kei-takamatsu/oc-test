<div class="content_view" style="margin:0 auto; max-width:500px;">
    <?php if($content['ReportContent']['movie_type'] == 'youtube'): ?>
        <iframe class="movie" src="//www.youtube.com/embed/<?php echo h($content['ReportContent']['movie_code']) ?>"
                frameborder="0" allowfullscreen></iframe>
    <?php else: ?>
        <iframe class="movie"
                src="//player.vimeo.com/video/<?php echo h($content['ReportContent']['movie_code']) ?>?title=0&amp;byline=0&amp;portrait=0&amp;color=9c1e1e"
                frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    <?php endif; ?>
</div>

<div class="content_edit_form clearfix" style="display: none;">
    <?php echo $this->Form->create('ReportContent', array(
            'inputDefaults' => array(
                    'label' => false, 'class' => 'form-control'
            ), 'onsubmit' => 'return false;', 'class' => 'movie_form'
    )) ?>

    <div class="form-group movie_url_form">
        <?php echo $this->Form->input('movie_code', array(
                'label' => 'YoutubeかVimeoの動画URLを入力してください', 'class' => 'form-control movie_url', 'value' => ''
        )); ?>
    </div>

    <div class="form-group" id="movie_btns">
        <button type="button"
                onclick="edit_movie($(this), '<?php echo $this->Html->url(array(
                        'controller' => 'report_contents', 'action' => 'movie_edit'
                )) ?>');"
                class="btn btn-info col-xs-3" style="margin-right:20px;">更新
        </button>
        <button type="button" onclick="cancel_edit($(this));" class="btn btn-info col-sm-3" style="margin-right:20px;">
            キャンセル
        </button>
    </div>

    <?php echo $this->Form->hidden('movie_type', array('class' => 'movie_type')) ?>
    <?php echo $this->Form->end(); ?>
</div>

