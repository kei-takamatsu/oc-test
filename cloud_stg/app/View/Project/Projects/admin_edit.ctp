<?php echo $this->element('admin/admin_project_menu') ?>

<?php echo $this->Form->create('Project', array(
        'type' => 'file', 'inputDefaults' => array(
                'class' => 'form-control', 'label' => false, 'div' => false
        ), 'class' => 'form-horizontal'
)); ?>
<div class="form-group">
    <label class="col-sm-2">プロジェクト名</label>

    <div class="col-sm-10">
        <?php echo $this->Form->input('project_name') ?>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2">カテゴリー</label>

    <div class="col-sm-10">
        <?php echo $this->Form->input('category_id'); ?>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2">目標金額</label>

    <div class="col-sm-10">
        <div class="input-group">
            <?php echo $this->Form->input('goal_amount'); ?>
            <span class="input-group-addon">円</span>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2">募集開始日</label>

    <div class="col-sm-10">
        <?php echo $this->Form->dateTime('collection_start_date', 'YMD', 'NONE', $start_option) ?>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2">募集終了日</label>

    <div class="col-sm-10">
        <?php echo $this->Form->dateTime('collection_end_date', 'YMD', 'NONE', $end_option) ?>
        <?php if(isset($errors['collection_end_date'][0])): ?>
            <br><span style="color:red"><?php echo $errors['collection_end_date'][0] ?></span>
        <?php endif; ?>
    </div>
</div>
<div class="form-group">
    <?php echo $this->Form->submit('更新', array('class' => 'col-xs-3 btn btn-success')) ?>
</div>
<?php echo $this->Form->end(); ?>

<br><br>