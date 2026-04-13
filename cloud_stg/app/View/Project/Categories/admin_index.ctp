<br>
<ul class="nav nav-tabs">
    <li class="active">
        <?php echo $this->Html->link('カテゴリー一覧', '/admin/categories') ?>
    </li>
    <li>
        <?php echo $this->Html->link('カテゴリー追加', '/admin/categories/add') ?>
    </li>
</ul>

<h2><?php echo __('%s一覧', __('カテゴリー')); ?></h2>

<table class="table">
    <tr>
        <th><?php echo $this->Paginator->sort('id', 'ID'); ?></th>
        <th><?php echo $this->Paginator->sort('name', 'カテゴリー名'); ?></th>
        <th class="actions"><?php echo __(''); ?></th>
    </tr>
    <?php foreach($categories as $category): ?>
        <tr>
            <td><?php echo h($category['Category']['id']); ?>&nbsp;</td>
            <td><?php echo h($category['Category']['name']); ?>&nbsp;</td>
            <td class="actions">
                <?php echo $this->Html->link(__('編集'), array(
                        'action' => 'edit', $category['Category']['id']
                ), array('class' => 'btn btn-success btn-sm')); ?>
                <?php echo $this->Form->postLink(__('削除'), array(
                        'action' => 'delete', $category['Category']['id']
                ), array('class' => 'btn btn-danger btn-sm'), __('削除しますか?', $category['Category']['id'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php echo $this->element('base/pagination') ?>

<br><br><br>
