<div class="report_edit_menu clearfix">
    <div class="<?php echo ($mode == 'base') ? 'active' : '' ?>">
        <p>
            <?php echo $this->Html->link('基本情報', array(
                    'action' => 'edit', $report['Report']['id']
            )) ?>
        </p>
    </div>
    <div class="<?php echo ($mode == 'detail') ? 'active' : '' ?>">
        <p>
            <?php echo $this->Html->link('詳細情報', array(
                    'action' => 'edit_detail', $report['Report']['id']
            )) ?>
        </p>
    </div>
    <div class="<?php echo ($mode == 'open') ? 'active' : '' ?>">
        <p>
            <?php if($report['Report']['open']): ?>
                <?php echo $this->Form->postLink(__('<span class="el-icon-eye-close"></span> 非公開'), array(
                        'action' => 'close', $report['Report']['id']
                ), array(
                        'escape' => false, 'div' => false
                ), __('非公開にしますか？')); ?>
            <?php else: ?>
                <?php echo $this->Form->postLink(__('<span class="el-icon-eye-open"></span> 公開'), array(
                        'action' => 'open', $report['Report']['id']
                ), array('escape' => false), __('公開しますか？')); ?>
            <?php endif ?>
        </p>
    </div>
</div>
