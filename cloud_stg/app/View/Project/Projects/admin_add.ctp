<br>
<ul class="nav nav-tabs">
    <li>
        <?php echo $this->Html->link('プロジェクト一覧', '/admin/projects') ?>
    </li>
    <li class="active">
        <?php echo $this->Html->link('プロジェクト追加', '/admin/projects/add') ?>
    </li>
</ul>

<h2>プロジェクトの追加</h2>
<?php
echo $this->Form->create('Project', array(
        'inputDefaults' => array(
                'class' => 'form-control', 'div' => false, 'label' => false
        ), 'class' => 'form-horizontal'
)) ?>

<div class="form-group">
    <label class="col-sm-2 control-label">プロジェクト名</label>

    <div class="col-sm-10">
        <?php echo $this->Form->input('project_name') ?>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">ユーザー</label>

    <div class="col-sm-10">
        <?php echo $this->Form->input('user_id') ?>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">カテゴリー</label>

    <div class="col-sm-10">
        <?php echo $this->Form->input('category_id') ?>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">パートナー</label>

    <div class="col-sm-10">
        <?php echo $this->Form->input('partner_id', array('empty' => '-----')) ?>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">目標金額</label>

    <div class="col-sm-10">
        <?php echo $this->Form->input('goal_amount'); ?>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <?php echo $this->Form->submit('登録', array('class' => 'btn btn-success col-xs-4')) ?>
    </div>
</div>
<?php echo $this->Form->end(); ?>

<br><br>