<?php echo $this->Html->css('grid', null, array('inline' => false)) ?>
<?php echo $this->Html->script('grid_position', array('inline' => false)) ?>

    <div class="bread">
        <?php echo $this->Html->link('プロジェクト', '/admin/admin_projects/') ?> &gt; トップページでの表示設定
    </div>
    <div class="setting_title">
        <h2>トップページでの表示設定</h2>
    </div>
<?php echo $this->element('admin/setting_project_main_menu', array('mode' => 'toppage')) ?>

    <div class="edit_project_toppage">

        <div class="clearfix search_box">
            <?php echo $this->Form->create('Project', array('inputDefaults' => array('class' => 'form-control'))) ?>

            <div class="search_input form-group">
                <?php echo $this->Form->input('project_name', array(
                        'label' => 'プロジェクトタイトル', 'required' => false
                )) ?>
            </div>
            <div class="search_btn form-group">
                <?php echo $this->Form->submit('検索', array('class' => 'btn btn-primary')) ?>
            </div>
            <?php echo $this->Form->end() ?>
        </div>

        <div id="grid_container" class="clearfix ">
            <?php foreach($projects as $idx => $project): ?>
                <?php echo $this->element('project_box/project_box_for_setting_toppage', array(
                        'project' => $project, 'top_projects' => json_decode($setting['toppage_projects_ids'], true)
                )) ?>
            <?php endforeach; ?>
        </div>

    </div>


    <div class="container">
        <?php echo $this->element('base/pagination') ?>
    </div>

<?php $this->start('script') ?>
    <script>
        $(document).ready(function () {
            grid_position();
        });
    </script>
<?php $this->end() ?>