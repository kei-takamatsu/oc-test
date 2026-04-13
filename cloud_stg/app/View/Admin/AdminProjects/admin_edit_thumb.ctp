<?php echo $this->Html->script('client_resize', array('inline' => false)) ?>

<div class="bread">
    <?php echo $this->Html->link('プロジェクト', '/admin/admin_projects/') ?> &gt;
    <?php echo $this->Html->link('プロジェクト一覧', '/admin/admin_projects/') ?> &gt;
    <?php echo $this->Html->link('プロジェクト編集', '/admin/admin_projects/edit/'.$project['Project']['id']) ?> &gt;
    サムネイル
</div>
<div class="setting_title">
    <h2>プロジェクトのサムネイル画像／動画の編集</h2>
</div>
<?php echo $this->element('admin/setting_project_menu', array('mode' => 'thumb')) ?>

<div class="setting_box">
    <div class="container">

        <?php echo $this->Form->create('Project', array(
                'type' => 'file', 'inputDefaults' => array(
                        'div' => false, 'class' => 'form-control'
                )
        )); ?>

        <div class="col-md-12">
            <h4>サムネイル表示パターン</h4>
            <div class="form-group">
                <?php echo $this->Form->input('thumbnail_type', array(
                        'type' => 'select', 'options' => array(
                                1 => '画像', 2 => '動画'
                        ), 'label' => false
                )) ?>
            </div>
        </div>

        <div class="clearfix">
            <div class="col-md-6">
                <h4>サムネイル画像</h4>
                <?php echo $this->element('project/setting/add_img') ?>
            </div>
            <div class="col-md-6">
                <h4>サムネイル動画</h4>
                <?php echo $this->element('project/setting/add_movie') ?>
            </div>
        </div>

        <br>
        <div class="form-group">
            <div class="col-xs-offset-3 col-xs-6">
                <input type="submit"
                       onclick="save_form_data($(this), event, '<?php echo $this->Html->url(array(
                               'action' => 'admin_edit_thumb/'.$project['Project']['id']
                       )) ?>'); return false;"
                       class="btn btn-primary btn-block" value="更新">
            </div>
        </div>

        <?php echo $this->Form->end(); ?>
    </div>
</div>


<?php $this->start('script') ?>
<script>
    $(document).ready(function () {
        resize_movie();
    });

    window.onresize = function () {
        resize_movie();
    };

    function check_movie_type(t) {
        var type = check_movie(t);
        if (!type) {
            t.val('');
            return;
        } else {
            t.val(type[1]);
            $('.movie_type').val(type[0]);
        }
    }
</script>
<?php $this->end(); ?>
