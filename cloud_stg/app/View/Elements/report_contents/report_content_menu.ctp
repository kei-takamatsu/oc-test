<div class="project_content_menu" style="margin:0 10px 5px 0; text-align:right;">
    <button type="button" onclick="content_edit($(this));" class="btn btn-sm btn-default-flat" autocomplete="off">
        <sapan class="el-icon-file-edit"></sapan>
    </button>
    <button type="button" onclick="trash($(this), '<?php echo $this->Html->url(array(
            'controller' => 'report_contents', 'action' => 'delete'
    )) ?>');"
            class="btn btn-sm btn-default-flat" autocomplete="off">
        <sapan class="el-icon-trash"></sapan>
    </button>
    <button type="button"
            onclick="move_up($(this), '<?php echo $this->Html->url(array(
                    'controller' => 'report_contents', 'action' => 'move_up', $content['ReportContent']['report_id']
            )) ?>');"
            class="btn btn-sm btn-default-flat" autocomplete="off">
        <sapan class=" el-icon-caret-up"></sapan>
    </button>
    <button type="button"
            onclick="move_down($(this), '<?php echo $this->Html->url(array(
                    'controller' => 'report_contents', 'action' => 'move_down', $content['ReportContent']['report_id']
            )) ?>');"
            class="btn btn-sm btn-default-flat" autocomplete="off">
        <sapan class="el-icon-caret-down"></sapan>
    </button>
    <button type="button" class="btn btn-default-flat btn-sm  move_btn" autocomplete="off">
        <sapan class="el-icon-move"></sapan>
    </button>
</div>