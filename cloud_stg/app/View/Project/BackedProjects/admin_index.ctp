<div class="row-fluid">
    <div class="span12">
        <h2><?php echo __('支援一覧'); ?></h2>

        <table class="table">
            <tr>
                <th><?php echo $this->Paginator->sort('id', 'ID'); ?></th>
                <th><?php echo $this->Paginator->sort('project_id', 'プロジェクト'); ?></th>
                <th><?php echo $this->Paginator->sort('user_id', '支援者'); ?></th>
                <th><?php echo $this->Paginator->sort('invest_amount', '支援金額'); ?></th>
                <th><?php echo $this->Paginator->sort('status', 'ステータス'); ?></th>
                <th><?php echo $this->Paginator->sort('created', '支援日時'); ?></th>
            </tr>
            <?php foreach($backed_projects as $backedProject): ?>
                <tr>
                    <td>
                        <?php echo h($backedProject['BackedProject']['id']); ?>
                    </td>
                    <td>
                        <?php echo $this->Html->link($backedProject['Project']['project_name'], array(
                                'controller' => 'projects', 'action' => 'view', $backedProject['Project']['id']
                        )); ?>
                    </td>
                    <td>
                        <a href="<?php echo $this->Html->url(array(
                                'controller' => 'users', 'action' => 'edit', 'admin' => true,
                                $backedProject['User']['id']
                        )) ?>">
                            <?php echo h(!empty($backedProject['User']['name']) ? $backedProject['User']['name'] : $backedProject['User']['nick_name']); ?>
                        </a>
                    </td>
                    <td>
                        <?php echo number_format(h($backedProject['BackedProject']['invest_amount'])); ?>
                    </td>
                    <td>
                        <?php echo h($backedProject['BackedProject']['status']); ?>
                    </td>
                    <td>
                        <?php echo date('Y年m月d日 H時i分', strtotime(h($backedProject['BackedProject']['created']))); ?>
                    </td>
                    <!--<td class="actions">
					<?php echo $this->Html->link(__('編集'), array(
                            'action' => 'edit', $backedProject['BackedProject']['id']
                    )); ?>
					<?php echo $this->Form->postLink(__('削除'), array(
                            'action' => 'delete', $backedProject['BackedProject']['id']
                    ), null, __('削除しますか？')); ?>
				</td>-->
                </tr>
            <?php endforeach; ?>
        </table>

        <?php echo $this->element('base/pagination') ?>
    </div>