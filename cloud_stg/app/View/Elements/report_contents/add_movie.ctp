<div class="none new_item new_movie">
    <?php echo $this->Form->create('ReportContent', array(
            'inputDefaults' => array(
                    'label' => false, 'class' => 'form-control'
            ), 'onsubmit' => 'return false;', 'class' => 'movie_form'
    )) ?>

    <h4>動画の追加</h4>

    <div class="form-group">
        <?php echo $this->Form->input('movie_code', array(
                'label' => 'YoutubeかVimeoの動画URLを入力してください', 'class' => 'form-control movie_url', 'value' => ''
        )); ?>
    </div>

    <div class="form-group" id="movie_btns">
        <?php $report_id = !(empty($report)) ? h($report['Report']['id']) : h($content['ReportContent']['report_id']) ?>
        <button type="button"
                onclick="save_movie($(this), '1', '<?php echo $this->Html->url(array(
                        'controller' => 'report_contents', 'action' => 'movie_add', $report_id
                )) ?>');"
                class="btn btn-info col-xs-3">追加
        </button>
    </div>

    <?php echo $this->Form->hidden('movie_type', array('class' => 'movie_type')) ?>
    <?php echo $this->Form->hidden('open', array('class' => 'open_mode')) ?>
    <?php echo $this->Form->hidden('type', array('value' => 'movie')) ?>
    <?php echo $this->Form->end(); ?>
    <br><br>
</div>