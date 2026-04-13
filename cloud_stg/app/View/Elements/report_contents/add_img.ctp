<div class="none new_item new_img">
    <?php echo $this->Form->create('ReportContent', array(
            'type' => 'file', 'inputDefaults' => array(
                    'label' => false, 'class' => 'form-control'
            ), 'onsubmit' => 'return false;', 'class' => 'img_form'
    )) ?>

    <h4>画像の追加</h4>
    <p class="hidden-xs">ドラッグアンドドロップでも追加できます</p>

    <div class="form-group">
        <?php $token = Security::hash(rand(100000, 999999), 'sha1', true) ?>
        <?php echo $this->Form->input('img', array(
                'type' => 'file', 'class' => 'client_resize',
                'onchange' => "client_resize($(this), event, 750, null, 'preview_img".$token."', 'img');"
        )); ?>
        <div id="preview_img<?php echo $token ?>" style="max-width:400px; margin-top:10px;"></div>
    </div>

    <div class="form-group">
        <?php echo $this->Form->input('img_caption', array(
                'label' => '画像の説明', 'class' => 'form-control img_caption'
        )); ?>
    </div>

    <div class="form-group">
        <?php $report_id = !(empty($report)) ? h($report['Report']['id']) : h($content['ReportContent']['report_id']) ?>
        <button type="button"
                onclick="save_img($(this), event, '1', '<?php echo $this->Html->url(array(
                        'controller' => 'report_contents', 'action' => 'img_add', $report_id
                )) ?>');"
                class="btn btn-info col-xs-3" style="margin-right:20px;">追加
        </button>
    </div>
    <?php echo $this->Form->hidden('open', array('class' => 'open_mode')) ?>
    <?php echo $this->Form->hidden('type', array('value' => 'img')) ?>
    <?php echo $this->Form->end(); ?>
    <br><br>
</div>