<div class="row-fluid">
    <div class="span9">
        <h2><?php echo __('Progrss Report'); ?></h2>
        <dl>
            <dt><?php echo __('Id'); ?></dt>
            <dd>
                <?php echo h($progrssReport['ProgrssReport']['id']); ?>
                &nbsp;
            </dd>
            <dt><?php echo __('Project'); ?></dt>
            <dd>
                <?php echo $this->Html->link($progrssReport['Project']['project_name'], array(
                        'controller' => 'projects', 'action' => 'view', $progrssReport['Project']['id']
                )); ?>
                &nbsp;
            </dd>
            <dt><?php echo __('Report'); ?></dt>
            <dd>
                <?php echo html_entity_decode($progrssReport['ProgrssReport']['report']); ?>
                &nbsp;
            </dd>
            <dt><?php echo __('Created'); ?></dt>
            <dd>
                <?php echo h($progrssReport['ProgrssReport']['created']); ?>
                &nbsp;
            </dd>
            <dt><?php echo __('Modified'); ?></dt>
            <dd>
                <?php echo h($progrssReport['ProgrssReport']['modified']); ?>
                &nbsp;
            </dd>
        </dl>
    </div>
    <div class="col-xs-3">
        <div class="well" style="padding: 8px 0; margin-top:8px;">
            <ul class="nav nav-list">
                <li class="nav-header"><?php echo __('Actions'); ?></li>
                <li><?php echo $this->Html->link(__('Edit %s', __('Progrss Report')), array(
                            'action' => 'edit', $progrssReport['ProgrssReport']['id']
                    )); ?> </li>
                <li><?php echo $this->Form->postLink(__('Delete %s', __('Progrss Report')), array(
                            'action' => 'delete', $progrssReport['ProgrssReport']['id']
                    ), null, __('Are you sure you want to delete # %s?', $progrssReport['ProgrssReport']['id'])); ?> </li>
                <li><?php echo $this->Html->link(__('List %s', __('Progrss Reports')), array('action' => 'index')); ?> </li>
                <li><?php echo $this->Html->link(__('New %s', __('Progrss Report')), array('action' => 'add')); ?> </li>
                <li><?php echo $this->Html->link(__('List %s', __('Projects')), array(
                            'controller' => 'projects', 'action' => 'index'
                    )); ?> </li>
                <li><?php echo $this->Html->link(__('New %s', __('Project')), array(
                            'controller' => 'projects', 'action' => 'add'
                    )); ?> </li>
            </ul>
        </div>
    </div>
</div>

