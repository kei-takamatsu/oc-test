<div id="sortable">

    <?php if($project_contents): ?>
        <?php foreach($project_contents as $idx => $c): ?>

            <div id="item_<?php echo h($c['ProjectContent']['id']) ?>"
                 name="<?php echo h($c['ProjectContent']['id']) ?>" class="sort_item">

                <?php echo $this->element('project_contents/add_here') ?>

                <div class="center-block project_content" style="max-width:700px; margin-top:10px;">

                    <?php echo $this->element('project_contents/project_content_menu', array('content' => $c)) ?>

                    <?php if($c['ProjectContent']['type'] == 'text'): ?>
                        <?php echo $this->element('project_contents/text', array('content' => $c)) ?>
                    <?php elseif($c['ProjectContent']['type'] == 'img'): ?>
                        <?php echo $this->element('project_contents/img', array('content' => $c)) ?>
                    <?php else: ?>
                        <?php echo $this->element('project_contents/movie', array('content' => $c)) ?>
                    <?php endif; ?>
                </div>

            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

