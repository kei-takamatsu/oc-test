<?php if($auth_user): ?>
    <h3><span class="el-icon-comment"></span> コメントの投稿</h3>
    <?php echo $this->Form->create('Comment', array(
            'url' => array(
                    'controller' => 'comments', 'action' => 'add'
            ), 'inputDefaults' => array('class' => 'form-control')
    )); ?>

    <?php echo $this->Form->input('user_id', array(
            'type' => 'hidden', 'value' => AuthComponent::user('id')
    )); ?>
    <?php echo $this->Form->input('project_id', array(
            'type' => 'hidden', 'value' => $project['Project']['id']
    )); ?>
    <div class="form-group">
        <?php echo $this->Form->input('comment', array(
                'type' => 'textarea', 'label' => false, 'required' => 'required'
        )); ?>
    </div>
    <?php echo $this->Form->submit('投稿', array(
            'div' => 'controls', 'class' => 'btn btn-primary col-xs-4'
    )); ?>
    <?php echo $this->Form->end(); ?>
<?php endif; ?>

