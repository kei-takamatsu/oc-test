<div class="container">
    <h1><?php echo h($project['Project']['project_name']); ?></h1>
</div>
<div class="clearfix sub">

    <a href="<?php echo $this->Html->url(array(
            'controller' => 'users', 'action' => 'view', $project['User']['User']['id']
    )) ?>">
        <?php echo $this->User->get_user_img_md($project['User'], 20) ?>
        <?php echo h($project['User']['User']['nick_name']) ?>
    </a>
    　
    　<span class="el-icon-tag"></span>
    <?php echo h($project['Category']['name'])?>
        <?php if($setting['cat_type_num'] == 2):?>
            x
            <?php echo h($project['Area']['name'])?>　
    <?php endif;?>
</div>

<div class="header_menu">
    <div onclick="location.href='<?php echo $this->Html->url('/projects/view/'.$project['Project']['id']) ?>';">
        <span class=" el-icon-website"></span><span class="hidden-xs"> 詳細</span>
    </div>
    <div onclick="location.href='<?php echo $this->Html->url('/projects/view/'.$project['Project']['id']
                                                             .'/comment') ?>';">
        <span class="el-icon-comment"></span><span class="hidden-xs"> コメント</span>
        <span class="label label-default hidden-xs">
			<?php echo number_format(h($project['Project']['comment_cnt'])) ?>
		</span>
    </div>
    <div onclick="location.href='<?php echo $this->Html->url('/projects/view/'.$project['Project']['id']
                                                             .'/backers') ?>';">
        <span class="el-icon-group"></span><span class="hidden-xs"> 支援者</span>
        <span class="label label-default hidden-xs">
			<?php echo number_format(h($project['Project']['backers'])) ?>
		</span>
    </div>
    <div onclick="location.href='<?php echo $this->Html->url('/projects/view/'.$project['Project']['id']
                                                             .'/report') ?>';">
        <span class="el-icon-bullhorn"></span><span class="hidden-xs"> 活動報告</span>
        <span class="label label-default hidden-xs">
			<?php echo number_format(h($project['Project']['report_cnt'])) ?>
		</span>
    </div>


</div>
