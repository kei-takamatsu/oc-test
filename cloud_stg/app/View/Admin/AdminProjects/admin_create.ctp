<?php echo $this->Html->script('client_resize', array('inline' => false)) ?>

<div class="bread">
    <?php echo $this->Html->link('プロジェクト', '/admin/admin_projects/') ?> &gt; プロジェクトの作成
</div>
<div class="setting_title">
    <h2>プロジェクトの作成</h2>
</div>
<?php echo $this->element('admin/setting_project_main_menu', array('mode' => 'create')) ?>

<div class="project_create">
    <div class="container">
        <div class="project_box img-rounded">

            <?php echo $this->Form->create('Project', array(
                    'type' => 'file', 'inputDefaults' => array(
                            'div' => false, 'class' => 'form-control'
                    )
            )); ?>

            <div class="clearfix">
                <div class="form-group">
                    <?php echo $this->Form->input('project_name', array('label' => 'プロジェクト名 <span class="label label-danger">必須</span>')) ?>
                </div>

                <div class="form-group">
                    <?php echo $this->Form->input('pic', array(
                            'type' => 'file', 'label' => '画像 <span class="label label-danger">必須</span>', 'class' => 'client_resize',
                            'onchange' => "client_resize($(this), event, 750, 450, 'preview_pic', 'pic');"
                    )); ?>
                    <br>
                    <div id="preview_pic"></div>
                </div>

                <div class="form-group">
                    <?php echo $this->Form->input('category_id', array('label' => 'カテゴリー1 <span class="label label-danger">必須</span>')); ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('area_id', array('label' => 'カテゴリー2')); ?>
                </div>

                <div class="form-group">
                    <?php echo $this->Form->input('goal_amount', array('label' => '目標金額 <span class="label label-danger">必須</span>')); ?>
                </div>

                <div class="form-group">
                    <?php echo $this->Form->input('description', array(
                            'type' => 'textarea', 'rows' => 5, 'label' => 'プロジェクト概要 <span class="label label-danger">必須</span>'
                    )); ?>
                </div>
            </div>

            <div class="form-group col-md-8 col-md-offset-2" style="margin-top:20px;">
                <input type="submit"
                       onclick="save_form_data_redirect($(this), event, '<?php echo $this->Html->url(array('action' => 'admin_create')) ?>', '<?php echo $this->Html->url(array('action' => 'admin_index')) ?>', 1); return false;"
                       class="btn btn-primary btn-block" value="登録">
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>



