<?php echo $this->Html->css('mypage', null, array('inline' => false)) ?>
<?php echo $this->Html->script('client_resize', array('inline' => false)) ?>
<?php echo $this->Html->script('jquery-ui.min', array('inline' => false)) ?>
<?php echo $this->Html->script('pj_contents', array('inline' => false)) ?>

<?php echo $this->element('mypage/mypage_project_menu', array('mode' => 'report')) ?>

    <h4 style="text-align: center;"><span class="el-icon-th"></span> 活動報告の編集</h4>

    <div class="report_edit_detail clearfix" style="min-height:500px;">
        <div class="report_edit_detail_header clearfix">
            <?php echo $this->element('mypage/report_edit_menu', array('mode' => 'detail')) ?>

            <div class="container clearfix">
                <div class="btn-group">
                    <button type="button" onclick="new_text_top($(this));" class="btn btn-default-flat">
                        <sapan class="el-icon-pencil"></sapan>
                    </button>

                    <button type="button" onClick="new_img_top($(this));" class="btn btn-default-flat">
                        <span class="el-icon-picture"></span>
                    </button>

                    <button type="button" onclick="new_movie_top($(this));" class="btn btn-default-flat">
                        <span class="el-icon-facetime-video"></span>
                    </button>
                </div>

                <?php echo $this->element('report_contents/add_text') ?>
                <?php echo $this->element('report_contents/add_img') ?>
                <?php echo $this->element('report_contents/add_movie') ?>
            </div>
        </div>

        <hr class="report_edit">

        <div class="container clearfix">
            <div id="project_contents">
                <?php echo $this->element('report_contents/contents_list') ?>
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
                    save_sort($(this), $(this).sortable('serialize'), '<?php echo $this->Html->url(array(
                            'controller' => 'report_contents', 'action' => 'sort', $report['Report']['id']
                    ))?>');
                },
                activate: function (event, ui) {
                    $('.add_here').hide();
                    $('.add_here_menu').hide();
                    $('.new_item').hide();
                }
            });
        });
    </script>
<?php $this->end() ?>