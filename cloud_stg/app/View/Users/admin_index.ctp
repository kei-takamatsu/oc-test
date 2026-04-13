<div class="row-fluid">
    <div class="row-fluid">
        <h2><?php echo __('%s一覧', __('ユーザー')); ?></h2>

        <table class="table">
            <tr>
                <th><?php echo $this->Paginator->sort('id', 'ID'); ?></th>
                <th><?php echo $this->Paginator->sort('nick_name', 'ニックネーム'); ?></th>
                <th><?php echo $this->Paginator->sort('name', '名前'); ?></th>
                <th><?php echo $this->Paginator->sort('email', 'メールアドレス'); ?></th>
                <th class="actions"><?php echo __(''); ?></th>
            </tr>
            <?php foreach($users as $user): ?>
                <tr>
                    <td><?php echo h($user['User']['id']); ?>&nbsp;</td>
                    <td><?php echo h($user['User']['nick_name']); ?>&nbsp;</td>
                    <td><?php echo h($user['User']['name']); ?>&nbsp;</td>
                    <td><?php echo h($user['User']['email']); ?>&nbsp;</td>
                    <td class="actions">
                        <?php echo $this->Html->link(__('編集'), array(
                                'action' => 'edit', $user['User']['id']
                        ), array('class' => 'btn btn-success')); ?>
                        <?php echo $this->Form->postLink(__('削除'), array(
                                'action' => 'delete', $user['User']['id']
                        ), array('class' => 'btn btn-danger'), __('削除しますか?', $user['User']['id'])); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php echo $this->element('base/pagination') ?>
    </div>
</div>