<?php echo $this->Html->css('mypage', null, array('inline' => false)) ?>

<?php echo $this->element('mypage/mypage_project_menu', array('mode' => 'report')) ?>


<h4><span class="el-icon-bullhorn"></span> 活動報告の追加</h4>

<div class="content container clearfix">
    <?php echo $this->Form->create('Report', array(
            'type' => 'file', 'inputDefaults' => array('class' => 'form-control')
    )); ?>
    <div class="form-group">
        <?php echo $this->Form->input('project_id', array(
                'type' => 'select', 'options' => $projects, 'selected' => $project_id, 'label' => 'プロジェクト'
        )) ?>
    </div>
    <div class="form-group">
        <?php echo $this->Form->input('title', array('label' => 'タイトル')) ?>
    </div>

    <div class="form-group clearfix">
        <div class="col-xs-offset-2 col-xs-8" style="margin-top:20px;">
            <?php echo $this->Form->submit(__('登録'), array('class' => 'btn btn-primary btn-block')); ?>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
