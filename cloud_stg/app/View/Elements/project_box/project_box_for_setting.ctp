<div class="grid_wrap">
    <?php echo $this->element('project_box/project_box', compact('project')) ?>

    <div class="buttons clearfix">
        <div class="left">
            <a class="btn btn-primary btn-block"
               href="<?php echo $this->Html->url('/admin/admin_projects/edit/'.$project['Project']['id']) ?>">
                <span class="el-icon-file-edit"></span> 編集
            </a>
        </div>
        <div class="right">
            <?php if($project['Project']['opened'] == 'no'): ?>
                <a class="btn btn-success btn-block"
                   href="<?php echo $this->Html->url('/admin/admin_projects/open/'.$project['Project']['id']) ?>">
                    <span class="el-icon-eye-open"></span> 公開
                </a>
            <?php elseif($project['Project']['stop'] != 1): ?>
                <a class="btn btn-danger btn-block"
                   href="<?php echo $this->Html->url('/admin/admin_projects/stop/'.$project['Project']['id']) ?>">
                    <span class="el-icon-eye-close"></span> 公開停止
                </a>
            <?php else: ?>
                <a class="btn btn-success btn-block"
                   href="<?php echo $this->Html->url('/admin/admin_projects/restart/'.$project['Project']['id']) ?>">
                    <span class="el-icon-eye-open"></span> 再開
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>


