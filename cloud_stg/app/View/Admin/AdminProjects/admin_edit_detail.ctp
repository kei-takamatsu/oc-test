<?php echo $this->Html->script('client_resize', array('inline' => false)) ?>
<?php echo $this->Html->script('jquery-ui.min', array('inline' => false)) ?>
<?php echo $this->Html->script('pj_contents', array('inline' => false)) ?>

<div class="bread">
    <?php echo $this->Html->link('プロジェクト', '/admin/admin_projects/') ?> &gt;
    <?php echo $this->Html->link('プロジェクト一覧', '/admin/admin_projects/') ?> &gt;
    <?php echo $this->Html->link('プロジェクト編集', '/admin/admin_projects/edit/'.$project['Project']['id']) ?> &gt;
    詳細情報
</div>
<div class="setting_title">
    <h2>プロジェクト詳細情報の編集</h2>
</div>
<?php echo $this->element('admin/setting_project_menu', array('mode' => 'detail')) ?>

<div class="project_contents">
    <div class="container clearfix">
        <br>

        <div class="btn-group">
            <button type="button" onclick="new_text_top($(this));" class="btn btn-default">
                <sapan class="el-icon-pencil"></sapan>
            </button>

            <button type="button" onClick="new_img_top($(this));" class="btn btn-default">
                <span class="el-icon-picture"></span>
            </button>

            <button type="button" onclick="new_movie_top($(this));" class="btn btn-default">
                <span class="el-icon-facetime-video"></span>
            </button>
        </div>

        <button type="button"
                onclick="location.href='<?php echo $this->Html->url('/admin/admin_project_contents/open_all/'
                                                                    .$project['Project']['id']) ?>';"
                class="btn btn-info">
            全部公開
        </button>


        <?php echo $this->element('project_contents/add_text') ?>
        <?php echo $this->element('project_contents/add_img') ?>
        <?php echo $this->element('project_contents/add_movie') ?>
    </div>
    <hr>

    <div class="container">
        <div class="clearfix">
            <div id="project_contents">
                <?php echo $this->element('project_contents/contents_list') ?>
            </div>
        </div>

    </div>
</div>


<?php $this->start('script') ?>
<script>
    $(document).ready(function () {
        resize_movie(500);

        //ドラッグによる順番変更
        $('#sortable').sortable({
            cursor: 'move',
            opacity: 0.7,
            placeholder: "placeholder",
            forcePlaceholderSize: true,
            cancel: false,
            handle: '.move_btn',
            update: function (event, ui) {
                save_sort($(this), $(this).sortable('serialize'), '<?php echo $this->Html->url('/admin/admin_project_contents/sort/'
                                                                                               .$project['Project']['id'])?>');
            },
            activate: function (event, ui) {
                $('.add_here').hide();
                $('.add_here_menu').hide();
                $('.new_item').hide();
            }
        });
    });

    window.onresize = function () {
        resize_movie(500);
    };
</script>
<?php $this->end() ?>


