    <?php if($this->Label->url_thumb($project['Project']['pic'], 400, 267)): ?>
            <?php echo $this->Label->image_thumb($project['Project']['pic'], 400, 267, array('class' => 'img')) ?>
    <?php else: ?>
            <?php echo $this->Html->image('project_no_image.png', array('class' => 'img')) ?>
    <?php endif; ?>
