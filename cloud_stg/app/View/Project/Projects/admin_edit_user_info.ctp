<?php echo $this->element('admin/admin_project_menu') ?>

<?php echo $this->Form->create('Project', array(
        'type' => 'file', 'inputDefaults' => array(
                'class' => 'form-control', 'div' => false
        )
)); ?>

<div class="form-group">
    <?php echo $this->Form->input('description', array(
            'type' => 'textarea', 'row' => 5, 'label' => 'プロジェクト概要'
    )); ?>
</div>

<div class="form-group">
    <?php echo $this->Form->input('return', array(
            'type' => 'textarea', 'row' => 5, 'label' => '支援パターン概要'
    )) ?>
</div>

<div class="form-group">
    <?php echo $this->Form->input('contact', array(
            'type' => 'textarea', 'row' => 5, 'label' => '連絡先'
    )) ?>
</div>

<div class="form-group">
    <?php echo $this->Form->submit('更新', array('class' => 'col-xs-4 btn btn-success')) ?>
</div>
<?php echo $this->Form->end(); ?>
<br><br><br>