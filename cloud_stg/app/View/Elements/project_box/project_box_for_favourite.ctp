<div class="grid_wrap">
    <?php echo $this->element('project_box/project_box', compact('project')) ?>

    <div class="buttons clearfix">
        <button class="btn btn-danger btn-block"
                onclick="location.href='<?php echo $this->Html->url(array(
                        'controller' => 'favourite_projects', 'action' => 'delete', $project['Project']['id']
                )) ?>'">
            <span class="el-icon-heart-empty"></span>
            お気に入り解除
        </button>

    </div>
</div>

