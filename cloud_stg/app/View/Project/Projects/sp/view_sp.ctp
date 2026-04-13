<?php echo $this->Html->css('sp/project_view', null, array('inline' => false)) ?>
<?php echo $this->Html->script('movie_resize', array('inline' => false)) ?>
<?php //echo $this->element( 'project/project_meta', array( 'project' => $project, 'mode' => $mode ) ) ?>

    <div class="tab_xs_header">
        <div class="menu" onclick="project_content_change('gaiyo');">概要</div>
        <div class="menu" onclick="project_content_change('detail');">詳細</div>
        <div class="menu" onclick="project_content_change('return');">リターン</div>
        <div class="menu other" onclick="project_menu_other();">その他 <b class="caret"></b></div>
        <div class="tab_xs_header_others">
            <div onclick="location.href='<?php echo $this->Html->url('/projects/view/'.$project['Project']['id']
                                                                     .'/comment') ?>'">
                <span class="el-icon-comment"></span>
                コメント一覧
            </div>
            <div onclick="location.href='<?php echo $this->Html->url('/projects/view/'.$project['Project']['id']
                                                                     .'/backers') ?>'">
                <span class="el-icon-group"></span>
                支援者一覧
            </div>
            <div onclick="location.href='<?php echo $this->Html->url('/projects/view/'.$project['Project']['id']
                                                                     .'/report') ?>'">
                <span class="el-icon-bullhorn"></span>
                活動報告
            </div>
        </div>
    </div>

    <div class="project">
        <div class="project_gaiyo project_content">
            <?php echo $this->element('project/project_thumbnail', array(
                    'project' => $project, 'mode' => $mode
            )) ?>

            <div class="project_header">
                <h1><?php echo h($project['Project']['project_name']); ?></h1>
                <div class="clearfix sub">
                    <span class="el-icon-tag"></span>
                    <?php echo h($project['Category']['name']); ?>　|　

                    <a href="<?php echo $this->Html->url(array(
                            'controller' => 'users', 'action' => 'view', $project['User']['User']['id']
                    )) ?>">
                        <?php echo $this->User->get_user_img_sm($project['User']) ?>
                        <?php echo h($project['User']['User']['nick_name']) ?>
                    </a>

                    <?php echo $this->element('project/graph', array('project' => $project)) ?>

                    ¥ <?php echo number_format(h($project['Project']['collected_amount'])); ?>
                    / ¥<?php echo number_format(h($project['Project']['goal_amount'])); ?>　
                    <span class="el-icon-group"></span> <?php echo h($project['Project']['backers']); ?>人　
                    <span class="el-icon-time"></span> <?php echo $this->Project->get_zan_day($project); ?>
                </div>
            </div>

            <div class="description clearfix">
                <p>
                    <?php echo nl2br(h($project['Project']['description'])) ?>
                </p>
                <div class="col-xs-offset-1 col-xs-10" style="margin-bottom:10px;">
                    <button class="btn btn-primary btn-sm btn-block" onclick="project_content_change('detail')">詳細
                    </button>
                </div>
            </div>


            <div class="user_info">
                <?php echo $this->element('project/sp/project_user') ?>
            </div>
        </div>

        <div class="project_return project_content">
            <?php echo $this->element('project/backing_levels', array('project' => $project)) ?>
        </div>

        <div class="project_detail project_content">
            <?php echo $this->element('project/project_detail', array(
                    'project' => $project, 'mode' => $mode
            )) ?>
        </div>

    </div>


<?php $this->start('script') ?>
    <script>
        $(document).ready(function () {
            resize_movie(750);
        });

        $('body').on('click', function (e) {
            if (!$(e.target).is('.other')) {
                if (!$(e.target).is('.tab_xs_header_others') && !$(e.target).closest('.tab_xs_header_others').size()) {
                    if ($('.tab_xs_header_others').is(':visible')) {
                        $('.tab_xs_header_others').slideUp(50);
                    }
                }
            }
        });

        function project_content_change(content_name) {
            $('.project_content').hide();
            $('.project_' + content_name).show();
            $('html,body').animate({scrollTop: 0}, 'fast');
            resize_movie(750);
            $('.tab_xs_header_others').slideUp(50);
        }

        function project_menu_other() {
            $('.tab_xs_header_others').slideToggle(100);
        }
    </script>
<?php $this->end() ?>