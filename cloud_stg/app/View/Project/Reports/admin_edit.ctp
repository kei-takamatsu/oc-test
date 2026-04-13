<?php echo $this->Html->script('ckeditor/ckeditor'); ?>

<h2>経過報告の編集</h2>
<?php echo $this->Form->create('ProgrssReport', array(
        'inputDefaults' => array(
                'label' => false, 'div' => false, 'class' => 'form-control'
        )
)); ?>

<div class="form-group">
    <?php echo $this->Form->input('project_id', array(
            'label' => 'プロジェクト', 'disabled' => 'disabled'
    )); ?>
</div>

<div class="form-group">
    <?php echo $this->Form->input('report', array(
            'id' => 'ckeditor', 'label' => '内容'
    )) ?>
    <script type="text/javascript">
        CKEDITOR.config.toolbar = [
            ['Source'],
            ['Undo', 'Redo'],
            ['Bold', 'Italic', 'Underline', 'Strike'],
            ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
            ['Link', 'Unlink', 'Image'],
            ['FontSize', 'TextColor', 'BGColor']
        ];
        var editor = CKEDITOR.replace('ckeditor');
    </script>
</div>

<?php echo $this->Form->hidden('id') ?>
<?php echo $this->Form->submit(__('更新'), array('class' => 'btn btn-success col-xs-4')); ?>
<?php echo $this->Form->end(); ?>

<br><br><br><br>
