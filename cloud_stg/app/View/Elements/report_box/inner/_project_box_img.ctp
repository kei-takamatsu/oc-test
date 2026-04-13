<div class="imgholder">
    <?php if($this->Label->url_thumb($report['Report']['thumbnail'], 400, 267)): ?>
        <?php if($report['Report']['open'] == 1): ?>
            <a href="<?php echo $this->Html->url(array(
                    'controller' => 'projects', 'action' => 'view', $report['Report']['project_id'], 'report_view',
                    $report['Report']['id']
            )) ?>">
                <?php echo $this->Label->image_thumb($report['Report']['thumbnail'], 400, 267, array('class' => 'img')); ?>
            </a>
        <?php else: ?>
            <?php echo $this->Label->image_thumb($report['Report']['thumbnail'], 400, 267, array('class' => 'img')); ?>
        <?php endif; ?>
    <?php else: ?>
        <?php echo $this->Html->image('report_back.png'); ?>
    <?php endif; ?>
</div>