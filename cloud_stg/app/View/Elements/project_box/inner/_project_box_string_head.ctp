<h2 class="grid_title">
    <?php if($project['Project']['opened'] == 'yes'): ?>
        <a href="<?php echo $this->Html->url('/projects/view/'.$project['Project']['id']) ?>">
            <?php echo h($project['Project']['project_name']); ?>
            <?php if(!empty($project['Setting']['site_name'])): ?>
                - <?php echo h($project['Setting']['site_name']) ?>
            <?php elseif(!empty($setting)): ?>
                - <?php echo h($setting['site_name']) ?>
            <?php endif; ?>
        </a>
    <?php else: ?>
        <?php echo h($project['Project']['project_name']); ?>
    <?php endif ?>
</h2>
