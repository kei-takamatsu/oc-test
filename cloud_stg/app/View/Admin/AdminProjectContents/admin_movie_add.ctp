<?php if(!empty($content)): ?>
    <div id="item_<?php echo h($content['ProjectContent']['id']) ?>"
         name="<?php echo $content['ProjectContent']['id'] ?>" class="sort_item">

        <?php echo $this->element('project_contents/add_here') ?>

        <div class="center-block project_content" style="max-width:700px; margin-top:0px;">

            <?php echo $this->element('project_contents/project_content_menu') ?>
            <?php echo $this->element('project_contents/movie') ?>

        </div>
    </div>
<?php endif; ?>