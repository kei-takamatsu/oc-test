<style>
    <?php
    $link_color = $setting['link_color'];
    $back1 = $setting['back1'];
    $back2 = $setting['back2'];
    $border_color = $setting['border_color'];

    $top_back = $setting['top_back']; //menu
    $top_color = $setting['top_color'];
    $top_alpha = $setting['top_alpha'];

    $footer_back = $setting['footer_back'];
    $footer_color = $setting['footer_color'];

    $graph_back = $setting['graph_back']; //達成率グラフの色

    //top_box
    $top_box_back = $graph_color = $setting['top_box_back'];
    $top_box_black = $graph_color = $setting['top_box_black'];
    $top_box_color = $graph_color = $setting['top_box_color'];
    $top_box_height = $graph_color = $setting['top_box_height'];

    $top_box_pc = $this->Label->url($setting['top_box_pc']);
    $top_box_sm = $this->Label->url($setting['top_box_sm']);
    ?>

    a, a:hover, a:focus {
        color: #<?php echo h($link_color)?>;
    }

    #menu_bar {
        background-color: <?php echo h($this->Color->change_rgba($top_back, $top_alpha));?>;
    }

    #menu_bar a, #menu_bar a:hover {
        color: #<?php echo h($top_color)?>;
    }

    .toppage .top_box:before,
    .toppage .top_box {
        height: <?php echo h($top_box_height)?>px;
    }

    .toppage .top_box {
        background-color: #<?php echo h($top_box_back)?>;
    }

    .toppage .top_box:before {
        background-color: <?php echo h($this->Color->change_rgba('212121', $top_box_black));?>;
    }

    .toppage .top_box h1 {
        color: #<?php echo h($top_box_color)?>;
    }

    .footer {
        background-color: #<?php echo h($footer_back)?>;
    }

    .footer a, .footer a:hover, .footer {
        color: #<?php echo h($footer_color)?>;
    }

    .progress-bar-warning {
        background-color: #<?php echo h($graph_back)?>;
    }

    .pickup_project_box, .reports,
    .search_box_wrap, .project_header,
    .profile .profile_header_wrap,
    .mypage_header .user_info, .pay_header {
        background: #<?php echo h($back1);?>;
    }

    .project_header .header_menu div,
    .mypage .mypage_content .sub_menu {
        background: #<?php echo h($back2);?>;
    }

    .project_header .header_menu div:hover,
    .mypage .mypage_content .sub_menu:hover,
    .mypage .mypage_content .sub_menu.hover,
    .mypage .mypage_content .sub_menu.active {
        background: #<?php echo h($this->Color->bright2($back2, 0.05));?>;
    }

    .mypage_header .menu {
        background: <?php echo h($this->Color->change_rgba($back2, 80));?>;
    }

    .mypage_header .menu div.mypage_menu:hover {
        background: <?php echo h($this->Color->change_rgba($this->Color->bright2($back2, 0.1), 80));?>;
    }

    #menu_bar, .pickup_project_box, .search_box_wrap, .mypage h4,
    .profile .profile_header_wrap, .pay_header {
        border-bottom: 1px solid #<?php echo h($border_color)?>;
    }

    .reports {
        border-top: 1px solid #<?php echo h($border_color)?>;
    }

    @media screen and (max-width: 748px) {
    <?php if(!empty($top_box_sm)):?>
        .toppage .top_box {
            background: url('<?php echo $top_box_sm?>') no-repeat center center;
            background-size: cover;
        }

        body.login {
            background: url('<?php echo $top_box_sm?>') no-repeat center center fixed;
            background-size: cover;
        }

    <?php endif;?>
    }

    @media screen and (min-width: 749px) {
    <?php if(!empty($top_box_pc)):?>
        .toppage .top_box {
            background: url(<?php echo $top_box_pc?>) no-repeat center center;
            background-size: cover;
        }

        body.login {
            background: url(<?php echo $top_box_pc?>) no-repeat center center fixed;
            background-size: cover;
        }

    <?php endif;?>
    }

    <?php
    switch($setting['content_position1']){
        case '下':
            echo '.top_box .content1{vertical-align:bottom;}';
            break;
        case '中':
            echo '.top_box .content1{vertical-align:middle;}';
            break;
        default:
            echo '.top_box .content1{vertical-align:top;}';
    }
    ?>
    <?php
    switch($setting['content_position2']){
        case '下':
            echo '.top_box .content2{vertical-align:bottom;}';
            break;
        case '中':
            echo '.top_box .content2{vertical-align:middle;}';
            break;
        default:
            echo '.top_box .content2{vertical-align:top;}';
    }
    ?>
</style>