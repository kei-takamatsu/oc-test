<div class="project_content_menu" style="margin:0 10px 5px 0; text-align:right;">
    <button type="button" onclick="content_edit($(this));" class="btn btn-default btn-sm" autocomplete="off">
        <sapan class="el-icon-file-edit"></sapan>
    </button>
    <button type="button"
            onclick="trash($(this), '<?php echo $this->Html->url('/admin/admin_project_contents/delete') ?>');"
            class="btn btn-default btn-sm" autocomplete="off">
        <sapan class="el-icon-trash"></sapan>
    </button>
    <button type="button"
            onclick="move_up($(this), '<?php echo $this->Html->url('/admin/admin_project_contents/move_up/'
                                                                   .$content['ProjectContent']['project_id']) ?>');"
            class="btn btn-default btn-sm" autocomplete="off">
        <sapan class=" el-icon-caret-up"></sapan>
    </button>
    <button type="button"
            onclick="move_down($(this), '<?php echo $this->Html->url('/admin/admin_project_contents/move_down/'
                                                                     .$content['ProjectContent']['project_id']) ?>');"
            class="btn btn-default btn-sm" autocomplete="off">
        <sapan class="el-icon-caret-down"></sapan>
    </button>
    <?php $open_url = $this->Html->url('/admin/admin_project_contents/content_open'); ?>
    <?php $close_url = $this->Html->url('/admin/admin_project_contents/content_close'); ?>
    <?php if($content['ProjectContent']['open'] == 1): ?>
        <button type="button" onclick="content_close($(this), '<?php echo $close_url ?>', '<?php echo $open_url ?>');"
                class="btn btn-default btn-sm" autocomplete="off">
            非公開にする
        </button>
    <?php else: ?>
        <button type="button" onclick="content_open($(this), '<?php echo $open_url ?>', '<?php echo $close_url ?>');"
                class="btn btn-default btn-sm" autocomplete="off">
            公開する
        </button>
    <?php endif; ?>
    <button type="button" class="btn btn-default move_btn btn-sm" autocomplete="off">
        <sapan class="el-icon-move"></sapan>
    </button>
</div>

