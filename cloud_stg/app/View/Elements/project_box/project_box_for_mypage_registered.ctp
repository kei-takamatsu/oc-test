<div class="grid_wrap">
    <?php echo $this->element('project_box/project_box', compact('project')) ?>

    <div class="buttons clearfix">
        <button class="btn btn-block btn-primary"
                onclick="location.href='<?php echo $this->Html->url(array(
                        'controller' => 'reports', 'action' => 'add', $project['Project']['id']
                )) ?>'">
            活動報告を追加
        </button>
    </div>
</div>
