<div class="grid_wrap">
    <?php echo $this->element('project_box/project_box', compact('project')) ?>

    <div class="buttons clearfix">
        <div class="left">
            <?php if($project['Project']['id'] == $setting['toppage_pickup_project_id']): ?>
                <a class="btn btn-danger btn-block"
                   href="<?php echo $this->Html->url(array(
                           'controller' => 'admin_projects', 'action' => 'admin_unset_pickup'
                   )) ?>">
                    ピックアップを解除
                </a>
            <?php else: ?>
                <a class="btn btn-primary btn-block"
                   href="<?php echo $this->Html->url(array(
                           'controller' => 'admin_projects', 'action' => 'admin_set_pickup', $project['Project']['id']
                   )) ?>">
                    ピックアップに設定
                </a>
            <?php endif; ?>
        </div>
        <div class="right">
            <?php if(!empty($top_projects) && in_array($project['Project']['id'], $top_projects)): ?>
                <a class="btn btn-danger btn-block"
                   href="<?php echo $this->Html->url(array(
                           'controller' => 'admin_projects', 'action' => 'admin_unset_top', $project['Project']['id']
                   )) ?>">
                    優先を解除
                </a>
            <?php else: ?>
                <a class="btn btn-success btn-block"
                   href="<?php echo $this->Html->url(array(
                           'controller' => 'admin_projects', 'action' => 'admin_set_top', $project['Project']['id']
                   )) ?>">
                    優先に設定
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>


