    <?php if($this->Label->url_thumb($report['Report']['thumbnail'], 400, 267)): ?>
        <?php if($report['Report']['open'] == 1): ?>
                <?php echo $this->Label->image_thumb($report['Report']['thumbnail'], 400, 267, array('class' => 'img')); ?>
        <?php else: ?>
            <?php echo $this->Label->image_thumb($report['Report']['thumbnail'], 400, 267, array('class' => 'img')); ?>
        <?php endif; ?>
    <?php else: ?>
        <?php echo $this->Html->image('report_back.png'); ?>
    <?php endif; ?>