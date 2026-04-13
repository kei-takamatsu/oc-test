<div class="imgholder">
    <?php if($this->Label->url_thumb($project['Project']['pic'], 400, 267)): ?>
        <a href="<?php echo $this->Html->url('/projects/view/'.$project['Project']['id']) ?>">
            <?php echo $this->Label->image_thumb($project['Project']['pic'], 400, 267, array('class' => 'img')) ?>
        </a>
    <?php else: ?>
        <a href="<?php echo $this->Html->url('/projects/view/'.$project['Project']['id']) ?>">
            <?php echo $this->Html->image('project_no_image.png', array('class' => 'img')) ?>
        </a>
    <?php endif; ?>
</div>