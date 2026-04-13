<div class="grid_footer_report clearfix">
    <div class="pull-left">
        <div class="user_img">
            <?php echo $this->User->get_user_img_md_from_user_id($report['Project']['user_id'], 70) ?>
        </div>
    </div>

    <div class="pull-right open_label">
        <?php if(empty($opened)): ?>
            <div class="pull-right" style="margin-right:10px;">
                <?php if($report['Report']['open']): ?>
                    <span class="label label-success" style="font-size:12px;">公開中</span>
                <?php else: ?>
                    <span class="label label-default" style="font-size:12px;">非公開中</span>
                <?php endif ?>
            </div>
        <?php endif ?>
    </div>

</div>