<div id="sortable">

    <?php if(!empty($report_contents)): ?>
        <?php foreach($report_contents as $idx => $c): ?>

            <div id="item_<?php echo h($c['ReportContent']['id']) ?>" name="<?php echo h($c['ReportContent']['id']) ?>"
                 class="sort_item">

                <?php echo $this->element('report_contents/add_here') ?>

                <div class="center-block project_content">

                    <?php echo $this->element('report_contents/report_content_menu', array('content' => $c)) ?>

                    <?php if($c['ReportContent']['type'] == 'text'): ?>
                        <?php echo $this->element('report_contents/text', array('content' => $c)) ?>
                    <?php elseif($c['ReportContent']['type'] == 'img'): ?>
                        <?php echo $this->element('report_contents/img', array('content' => $c)) ?>
                    <?php else: ?>
                        <?php echo $this->element('report_contents/movie', array('content' => $c)) ?>
                    <?php endif; ?>
                </div>

            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

