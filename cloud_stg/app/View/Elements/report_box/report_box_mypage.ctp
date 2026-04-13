<div class="grid_wrap_report">
    <?php echo $this->element('report_box/report_box', compact('report')) ?>

    <div class="buttons clearfix">
        <div class="left">
            <?php echo $this->Html->link(__('<span class="el-icon-file-edit"></span> 編集'), array(
                    'action' => 'edit', $report['Report']['id']
            ), array(
                    'class' => 'btn btn-primary btn-block', 'escape' => false
            )); ?>
        </div>
        <div class="right">
            <?php echo $this->Form->postLink(__('<span class="el-icon-trash"></span> 削除'), array(
                    'action' => 'delete', $report['Report']['id']
            ), array(
                    'class' => 'btn btn-danger btn-block', 'escape' => false
            ), __('削除しますか？')); ?>
        </div>
    </div>
</div>
