<div class="row-fluid">
    <div class="span9">
        <?php echo $this->Form->create('Comment', array('class' => 'form-horizontal')); ?>
        <fieldset>
            <legend><?php echo __('Add %s', __('Comment')); ?></legend>
            <?php
            echo $this->Form->input('user_id');
            echo $this->Form->input('project_id');
            echo $this->Form->input('comment', array(
                    'required' => 'required',
                    'helpInline' => '<span class="label label-important">'.__('Required').'</span>&nbsp;'
            ));
            ?>
            <?php echo $this->Form->submit(__('Submit')); ?>
        </fieldset>
        <?php echo $this->Form->end(); ?>
    </div>
    <div class="col-xs-3">
        <div class="well" style="padding: 8px 0; margin-top:8px;">
            <ul class="nav nav-list">
                <li class="nav-header"><?php echo __('Actions'); ?></li>
                <li><?php echo $this->Html->link(__('List %s', __('Comments')), array('action' => 'index')); ?></li>
                <li><?php echo $this->Html->link(__('List %s', __('Projects')), array(
                            'controller' => 'projects', 'action' => 'index'
                    )); ?></li>
                <li><?php echo $this->Html->link(__('New %s', __('Project')), array(
                            'controller' => 'projects', 'action' => 'add'
                    )); ?></li>
            </ul>
        </div>
    </div>
</div>