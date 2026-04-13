<?php echo $this->Html->css('project_view', null, array('inline' => false)) ?>
<?php echo $this->Html->css('grid_report', null, array('inline' => false)) ?>

<h2 class="title"><span class="el-icon-bullhorn"></span> 活動報告</h2>

<?php if(!empty($reports)): ?>
    <div id="grid_container_report" class="clearfix">
        <?php foreach($reports as $report): ?>
            <div class="grid_wrap_report center">
                <?php echo $this->element('report_box/report_box_opened', array('report' => $report)) ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php echo $this->element('base/pagination') ?>
<?php else: ?>
    <p>まだ経過報告はありません。</p>
    <br>
<?php endif; ?>










