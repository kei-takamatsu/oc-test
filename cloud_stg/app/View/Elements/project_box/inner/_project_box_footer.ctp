<div class="grid_footer clearfix">
    ¥<?php echo number_format(h($project['Project']['collected_amount'])); ?>
    　
    <span class="el-icon-group"></span>
    <?php echo h($project['Project']['backers']); ?>人

    　
    <span class="el-icon-time"></span>
    <?php echo $this->Project->get_zan_day($project); ?>
</div>