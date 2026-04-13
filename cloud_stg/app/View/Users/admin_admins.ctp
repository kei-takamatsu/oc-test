<br>
<ul class="nav nav-tabs">
    <li class="active">
        <?php echo $this->Html->link('管理者一覧', '/admin/users/admins') ?>
    </li>
    <li>
        <?php echo $this->Html->link('管理者追加', '/admin/users/addAdmin') ?>
    </li>
</ul>

<h2><?php echo __('%s一覧', __('管理者')); ?></h2>

<?php foreach($users as $user): ?>
    <div class="media">
        <h4>
            <?php echo h($user['User']['id']); ?> -
            <?php echo h($user['User']['name']); ?>
        </h4>

        <div class="col-md-8">
            メール：<?php echo h($user['User']['email']); ?>
        </div>
        <div class="col-md-4 action">
            <?php echo $this->Html->link(__('編集'), array(
                    'action' => 'editAdmin', $user['User']['id']
            ), array('class' => 'btn btn-success')); ?>
            <?php echo $this->Form->postLink(__('削除'), array(
                    'action' => 'delete', $user['User']['id']
            ), array('class' => 'btn btn-danger'), __('削除しますか？', $user['User']['id'])); ?>
        </div>
    </div>
<?php endforeach; ?>

<br><br>
	