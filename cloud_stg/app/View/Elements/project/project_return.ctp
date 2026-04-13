<div class="return">
    <div class="clearfix">
        <div class="left">
            <p class="return_price">
                <?php echo number_format($backing_level['BackingLevel']['invest_amount']); ?> 円
            </p>
        </div>
        <div class="right" style="text-align:right;">
            <?php if(!empty($backing_level['BackingLevel']['max_count'])): ?>
                <p>
                    <?php echo $this->Project->get_zan_back_label($backing_level) ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <div class="clearfix">
        <p>
            <span class="el-icon-group"></span>
            支援者：<?php echo number_format($backing_level['BackingLevel']['now_count']) ?>人
        </p>

        <?php echo nl2br(h($backing_level['BackingLevel']['return_amount'])); ?>
    </div>
</div>