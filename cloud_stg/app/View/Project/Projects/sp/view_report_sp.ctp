<?php echo $this->Html->css('sp/project_view', null, array('inline' => false)) ?>
<?php echo $this->Html->css('grid_report', null, array('inline' => false)) ?>

<?php echo $this->element('project/sp/tab_header_back') ?>
<div class="project">
    <h3><span class="el-icon-bullhorn"></span> 活動報告</h3>

    <?php if(!empty($reports)): ?>
        <div id="grid_container_report" class="clearfix">
            <?php foreach($reports as $report): ?>
                <div class="grid_wrap_report center">
                    <?php echo $this->element('report_box/report_box_opened', array('report' => $report)) ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div style="margin:0 10px;">
            <?php echo $this->element('base/pagination') ?>
        </div>
    <?php else: ?>
        <div style="padding:20px 10px;">
            <p>まだ経過報告はありません。</p>
            <br>
        </div>
        <hr>
    <?php endif; ?>
</div>










