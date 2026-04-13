<div class="project_header">
    <h1><?php echo h($project['Project']['project_name']); ?></h1>
    <div class="clearfix sub">
        <span class="el-icon-tag"></span>
        <?php echo h($project['Category']['name']); ?>　|　

        <a href="<?php echo $this->Html->url(array(
                'controller' => 'users', 'action' => 'view', $project['User']['id']
        )) ?>">
            <span class="el-icon-child"></span>
            <?php echo h($project['User']['nick_name']) ?>
        </a>
    </div>
</div>