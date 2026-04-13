<div class="clearfix">
    <h2 class="grid_title_report">
        <?php echo h($report['Report']['title']); ?>
    </h2>

    <div class="pull-left">
        <div class="report_date">
            <?php echo date('Y年m月d日', strtotime($report['Report']['created'])) ?>
        </div>

        <?php if(empty($opened)): ?>
            <div class="project_name">
                <?php echo h($report['Project']['project_name']) ?>
            </div>
        <?php endif ?>
    </div>


</div>

