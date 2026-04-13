<div onmouseover="display_add_here($(this));" onmouseout="remove_add_here($(this));"
     style="min-height:30px; margin-top:15px;">
    <div class="add_here none">
        <button type="button" onclick="display_add_here_menu($(this));"
                class="btn btn btn-default-flat btn-block add_here_btn">
            ここに追加
        </button>
    </div>
    <div class="add_here_menu none" style="background-color:#eee; padding:10px;">
        <div class="btn-group">
            <button type="button" onclick="new_text($(this));" class="btn btn-default-flat">
                <sapan class="el-icon-pencil"></sapan>
            </button>

            <button type="button" onClick="new_img($(this));" class="btn btn-default-flat">
                <span class="el-icon-picture"></span>
            </button>

            <button type="button" onclick="new_movie($(this));" class="btn btn-default-flat">
                <span class="el-icon-facetime-video"></span>
            </button>
        </div>

        <div onclick="close_add_here_menu($(this));" class="pull-right" style="padding:10px 10px 0 0;">
            <span class="el-icon-remove"></span>
        </div>
    </div>

    <?php echo $this->element('report_contents/add_text') ?>
    <?php echo $this->element('report_contents/add_img') ?>
    <?php echo $this->element('report_contents/add_movie') ?>
</div>