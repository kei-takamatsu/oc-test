<?php if(!empty($content)): ?>
    <div id="item_<?php echo h($content['ReportContent']['id']) ?>" name="<?php echo $content['ReportContent']['id'] ?>"
         class="sort_item">

        <?php echo $this->element('report_contents/add_here') ?>

        <div class="center-block project_content">

            <?php echo $this->element('report_contents/report_content_menu') ?>
            <?php echo $this->element('report_contents/img') ?>

        </div>
    </div>
<?php endif; ?>