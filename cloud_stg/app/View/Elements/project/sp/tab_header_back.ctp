<div class="tab_xs_header">
    <div class="menu menu_back" onclick="location.href='<?php echo $this->Html->url(array(
            'action' => 'view', $project['Project']['id']
    )) ?>';">
        <span class="el-icon-chevron-left"></span>
        <?php echo h($project['Project']['project_name']); ?>
    </div>
</div>