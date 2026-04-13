<?php $this->start('script') ?>
    <script>
        window.onresize = function () {
            //menu非表示
            if ($(window).width() > 748) {
                $('#sub_menu').hide();
            }

            <?php $controller = $this->request->params['controller']?>
            <?php $action = $this->request->params['action']?>

            <?php if($controller == 'base' && $action == 'index'):?>
            top_box_position();
            grid_position();
            top_report_position();
            <?php endif;?>

            <?php if(
            ($controller == 'projects' && $action == 'index')
            || ($controller == 'users' && $action == 'mypage')
            || ($controller == 'projects' && $action == 'registered')
            || ($controller == 'favourite_projects' && $action == 'index')
            || ($controller == 'users' && $action == 'view')
            || ($controller == 'users' && $action == 'registered')
            ):?>
            grid_position();
            <?php endif;?>

            <?php if(
            ($controller == 'reports' && $action == 'index')
            ):?>
            grid_position_report();
            <?php endif;?>

            <?php if($controller == 'projects' && $action == 'view'):?>
            resize_movie(750);
            <?php endif;?>

            <?php if($controller == 'reports' && $action == 'edit_detail'):?>
            resize_movie(500);
            <?php endif;?>

        };

        //サブメニュー表示切替
        function toggle_sub_menu() {
            $('#sub_menu').slideToggle(100);
            event.stopPropagation();
        }

        $('body').click(function (e) {
            if (!$(e.target).is('.icon-bar') && !$(e.target).is('.el-icon-lines')) {
                if (!$(e.target).is('#sub_menu') && !$(e.target).closest('#sub_menu').size()) {
                    if ($('#sub_menu').is(':visible')) {
                        $('#sub_menu').slideUp(50);
                    }
                }
            }
        });
    </script>
<?php $this->end() ?>