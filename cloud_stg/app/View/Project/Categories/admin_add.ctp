<br>
<ul class="nav nav-tabs">
    <li>
        <?php echo $this->Html->link('カテゴリー一覧', '/admin/categories') ?>
    </li>
    <li class="active">
        <?php echo $this->Html->link('カテゴリー追加', '/admin/categories/add') ?>
    </li>
</ul>

<h2><?php echo __('%sの追加', __('カテゴリー')); ?></h2>

<?php echo $this->Form->create('Category'); ?>
<div class="form-group">
    <?php echo $this->Form->input('name', array(
            'required' => 'required', 'label' => 'カテゴリー名', 'class' => 'form-control'
    )); ?>
</div>

<div class="form-group">
    <?php echo $this->Form->submit(__('登録'), array('class' => 'btn btn-success col-xs-4')); ?>
</div>

<?php echo $this->Form->end(); ?>

<br><br><br>
