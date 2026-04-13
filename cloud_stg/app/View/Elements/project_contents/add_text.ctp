<div class="none new_item clearfix new_text">
    <?php echo $this->Form->create('ProjectContent', array(
            'inputDefaults' => array(
                    'label' => false, 'class' => 'form-control'
            ), 'onsubmit' => 'return false;', 'class' => 'text_form'
    )) ?>

    <h4>テキストの追加</h4>

    <?php echo $this->element('project_contents/text_btn_menu') ?>

    <div class="form-group">
        <?php echo $this->Form->input('txt_content', array(
                'type' => 'textarea', 'rows' => 5, 'class' => 'form-control text_content', 'value' => ''
        )); ?>
    </div>

    <div class="form-group">
        <?php $project_id = !(empty($project)) ? h($project['Project']['id']) : h($content['ProjectContent']['project_id']) ?>
        <button type="button"
                onclick="save_text($(this), '0', '<?php echo $this->Html->url('/admin/admin_project_contents/text_add/'
                                                                              .$project_id) ?>');"
                class="btn btn-info col-xs-3" style="margin-right:20px;">下書き
        </button>
        <button type="button"
                onclick="save_text($(this), '1', '<?php echo $this->Html->url('/admin/admin_project_contents/text_add/'
                                                                              .$project_id) ?>');"
                class="btn btn-primary col-xs-3">公開
        </button>
    </div>
    <?php echo $this->Form->hidden('open', array('class' => 'open_mode')) ?>
    <?php echo $this->Form->hidden('type', array('value' => 'text')) ?>
    <?php echo $this->Form->end(); ?>
    <br><br>
</div>