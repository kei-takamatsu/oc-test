<?php echo $this->Html->css('mypage', null, array('inline' => false)) ?>
<?php echo $this->Html->css('grid_report', null, array('inline' => false)) ?>
<?php echo $this->Html->script('grid_position', array('inline' => false)) ?>

<?php echo $this->element('mypage/mypage_project_menu', array('mode' => 'report')) ?>

<h4><span class="el-icon-bullhorn"></span> 活動報告</h4>

<div id="grid_container_report" class="clearfix">
    <?php foreach($reports as $report): ?>
        <?php echo $this->element('report_box/report_box_mypage', array('report' => $report)) ?>
    <?php endforeach; ?>
</div>

<div class="container">
    <?php echo $this->element('base/pagination') ?>
</div>


<?php $this->start('script') ?>
<script>
    $(document).ready(function () {
        grid_position_report();
    });
</script>
<?php $this->end() ?>

