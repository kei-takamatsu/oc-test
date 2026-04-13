<div class="none new_item clearfix new_text">
    <?php echo $this->Form->create('ReportContent', array(
            'inputDefaults' => array(
                    'label' => false, 'class' => 'form-control'
            ), 'onsubmit' => 'return false;', 'class' => 'text_form'
    )) ?>

    <h4>テキストの追加</h4>

    <?php echo $this->element('report_contents/text_btn_menu') ?>

    <div class="form-group">
        <?php echo $this->Form->input('txt_content', array(
                'type' => 'textarea', 'rows' => 5, 'class' => 'form-control text_content', 'value' => ''
        )); ?>
    </div>

    <div class="form-group">
        <?php $report_id = !(empty($report)) ? h($report['Report']['id']) : h($content['ReportContent']['report_id']) ?>
        <button type="button"
                onclick="save_text($(this), '1', '<?php echo $this->Html->url(array(
                        'controller' => 'report_contents', 'action' => 'text_add', $report_id
                )) ?>');"
                class="btn btn-primary col-xs-3">追加
        </button>
    </div>
    <?php echo $this->Form->hidden('open', array('class' => 'open_mode')) ?>
    <?php echo $this->Form->hidden('type', array('value' => 'text')) ?>
    <?php echo $this->Form->end(); ?>
    <br><br>
</div>