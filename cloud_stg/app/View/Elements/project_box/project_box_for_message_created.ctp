<div class="grid_wrap">
    <?php echo $this->element('project_box/project_box', compact('project')) ?>

    <div class="buttons clearfix">
        <button class="btn btn-block btn-primary"
                onclick="location.href='<?php echo $this->Html->url(array(
                        'action' => 'backers', $project['Project']['id']
                )) ?>'">
            選択
        </button>
    </div>
</div>
