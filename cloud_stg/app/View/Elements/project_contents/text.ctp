<div class="content_view" style="margin:0 auto; max-width:500px; word-break:break-all;">
    <?php echo nl2br($this->Project->sanitize($content['ProjectContent']['txt_content'])) ?>
</div>

<div class="content_edit_form clearfix" style="display: none;">
    <?php echo $this->Form->create('ProjectContent', array(
            'inputDefaults' => array(
                    'label' => false, 'class' => 'form-control'
            ), 'onsubmit' => 'return false;', 'class' => 'text_form'
    )) ?>

    <?php echo $this->element('project_contents/text_btn_menu') ?>

    <div class="form-group text_content_form">
        <?php echo $this->Form->input('txt_content', array(
                'type' => 'textarea', 'rows' => 10, 'class' => 'form-control text_content',
                'value' => $content['ProjectContent']['txt_content']
        )); ?>
    </div>

    <div class="form-group" id="movie_btns">
        <button type="button"
                onclick="edit_text($(this), '<?php echo $this->Html->url('/admin/admin_project_contents/text_edit') ?>');"
                class="btn btn-info col-xs-3" style="margin-right:20px;">更新
        </button>
        <button type="button" onclick="cancel_edit($(this));" class="btn btn-info col-sm-3" style="margin-right:20px;">
            キャンセル
        </button>
    </div>

    <?php echo $this->Form->end(); ?>
</div>

