<br>
<ol class="breadcrumb">
    <li><a href="<?php echo $this->Html->url('/admin/users/mypage') ?>">管理トップ</a></li>
    <li><a href="<?php echo $this->Html->url('/admin/categories') ?>">カテゴリー一覧</a></li>
    <li class="active">カテゴリーの編集</li>
</ol>

<h2>カテゴリー編集</h2>
<?php echo $this->Form->create('Category', array('inputDefaults' => array('class' => 'form-control'))); ?>

<div class="form-group">
    <?php echo $this->Form->input('name', array('label' => 'カテゴリー名')); ?>
</div>

<dic class="form-group">
    <?php echo $this->Form->submit(__('登録'), array('class' => 'btn btn-success col-xs-4')); ?>
</dic>

<? echo $this->Form->hidden('id'); ?>
<?php echo $this->Form->end(); ?>

<br><br><br>
