<?php $this->start('ogp') ?>
<meta property="og:title" content="<?php echo h($setting['site_title']) ?>"/>
<meta property="og:type" content="website"/>
<meta property="og:url" content="<?php echo $this->Html->url('/', true) ?>"/>
<meta property="og:image"
      content="<?php echo ($this->Label->link($setting['facebook_img'])) ? $this->Label->url($setting['facebook_img'], true) : '' ?>"/>
<meta property="og:site_name" content="<?php echo h($setting['site_name']) ?>"/>
<meta property="og:description" content="<?php echo h($setting['site_description']) ?>"/>
<?php $this->end() ?>

<?php echo $this->Html->css('top', null, array('inline' => false)) ?>
<?php echo $this->Html->css('grid', null, array('inline' => false)) ?>
<?php echo $this->Html->css('grid_report', null, array('inline' => false)) ?>
<?php echo $this->Html->script('grid_position', array('inline' => false)) ?>

<?php if($smart_phone): ?>
    <?php echo $this->Html->css('sp/grid_sp', null, array('inline' => false)) ?>
<?php endif ?>

<!--<div class="toppage">-->
<!--
    <div class="top_box">
        <div class="content1">
            <?php $this->Setting->display_content($setting, 1); ?>
        </div>
        <?php if($setting['top_box_content_num'] == 2): ?>
            <div class="content2">
                <?php $this->Setting->display_content($setting, 2); ?>
            </div>
        <?php endif ?>
    </div>
-->
        <section id="top_featured_wrap">
    <?php if(!empty($pickup_pj)): ?>

            <?php echo $this->element('project_box/pickup_project', array('project' => $pickup_pj)) ?>

    <?php endif; ?>


            <div class="top_pickup_wrap">
                <div class="top_pickup_header">
                    <a href="about.php" class="top_pickup_about">
                        〇〇〇とは
                    </a>
                    <a href="guide.php" class="top_pickup_guide">
                        ご利用ガイド
                    </a>
                    <div class="top_header_users">
                        <a href="login.php" class="thu_login">
                            <img src="/asset/image/icon/login.svg">
                        </a>
                        <a href="register.php" class="thu_regist">
                            <img src="/asset/image/icon/regist.svg">
                        </a>
                    </div>
                </div>
                <div class="top_pickup_cont">
                    <h3 class="tpc_ttl">
                        PICK UP
                    </h3>
                    <div class="tpc_box">

                        <a href="project.php" class="tpc_block">
                            <div class="tpc_thum">
                                <img src="/asset/image/test/2.jpg">
                            </div>
                            <div class="tpc_detail">
                                <div class="tpcd_txt">
                                    プロジェクトのタイトルが入りますプロジェクトのタイトルが入りますプロジェクトのタイトルが入ります。
                                </div>
                                <div class="pu_box">
                                    <div class="top_box_money_pu">
                                        ￥1,300,888
                                    </div>
                                    <div class="top_box_bar_wrap_pu">
                                        <div class="top_box_bar_pu">
                                            <span class="bar_80"></span>
                                        </div>
                                        <div class="top_box_bar_num_pu">
                                            80%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="project.php" class="tpc_block">
                            <div class="tpc_thum">
                                <img src="/asset/image/test/9.jpg">
                            </div>
                            <div class="tpc_detail">
                                <div class="tpcd_txt">
                                    プロジェクトのタイトルが入りますプロジェクトのタイトルが入りますプロジェクトのタイトルが入りますタイトルが入りますタイトルが入ります。
                                </div>
                                <div class="pu_box">
                                    <div class="top_box_money_pu">
                                        ￥1,300,888
                                    </div>
                                    <div class="top_box_bar_wrap_pu">
                                        <div class="top_box_bar_pu">
                                            <span class="bar_80"></span>
                                        </div>
                                        <div class="top_box_bar_num_pu">
                                            80%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="project.php" class="tpc_block">
                            <div class="tpc_thum">
                                <img src="/asset/image/test/10.jpg">
                            </div>
                            <div class="tpc_detail">
                                <div class="tpcd_txt">
                                    プロジェクトのタイトルが入りますプロジェクトのタイトルが入りますプロジェクトのタイトルが入ります。
                                </div>
                                <div class="pu_box">
                                    <div class="top_box_money_pu">
                                        ￥1,300,888
                                    </div>
                                    <div class="top_box_bar_wrap_pu">
                                        <div class="top_box_bar_pu">
                                            <span class="bar_80"></span>
                                        </div>
                                        <div class="top_box_bar_num_pu">
                                            80%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="project.php" class="tpc_block tpc_bnone">
                            <div class="tpc_thum">
                                <img src="/asset/image/test/11.jpg">
                            </div>
                            <div class="tpc_detail">
                                <div class="tpcd_txt">
                                    プロジェクトのタイトルが入りますプロジェクトのタイトルが入りますプロジェクトのタイトルが入ります。
                                </div>
                                <div class="pu_box">
                                    <div class="top_box_money_pu">
                                        ￥1,300,888
                                    </div>
                                    <div class="top_box_bar_wrap_pu">
                                        <div class="top_box_bar_pu">
                                            <span class="bar_80"></span>
                                        </div>
                                        <div class="top_box_bar_num_pu">
                                            80%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>

                    </div>
                </div>
            </div>
        </section>





        <!--============================== ▼activity =================================-->

        <section class="top_report_wrap">
            <div class="top_report_inner">
                <div class="top_report_head">
                    <h3 class="trt_en">
                        ACTIVITY REPORT
                    </h3>
                    <div class="trt_jp">
                        活動報告
                    </div>
                </div>

                <div class="scroll-box">
                    <div class="sec_top_report">
                <?php foreach($reports as $report): ?>

                        <?php echo $this->element('report_box/_report_box', array('report' => $report)) ?>

                <?php endforeach; ?>
                    </div>

                </div>
            
            </div>
        </section>




		<!--============================== ▼contents =================================-->
		<div id="top_main">      
            <section class="top_main_wrap">
                <div class="top_main_inner">
                    
                    <div class="column_project_list_top">
                        <div class="cplt_box_wrap">
        <?php foreach($projects as $idx => $project): ?>
            <?php if(isset($project['Project'])): ?>
                <?php echo $this->element('project_box/project_box_for_normal2', compact('project')) ?>
            <?php endif; ?>
        <?php endforeach; ?>


                        </div>

                        <div class="top_list_more">
                            <a href="<?php echo $this->Html->url('/projects') ?>" class="top_list_btn">
                                <span>全て見る</span>
                            </a>
                        </div>

                    </div>

                    <div class="column_side_top">
                        <div class="side_content">
                            <h3 class="sc_ttl">
                                キーワード
                            </h3>
                            <div class="sc_inner_kw">
                                <a href="list.php" class="sckw">映像</a>
                                <a href="list.php" class="sckw">音楽</a>
                                <a href="list.php" class="sckw">アート</a>
                                <a href="list.php" class="sckw">写真</a>
                                <a href="list.php" class="sckw">アイドル</a>
                                <a href="list.php" class="sckw">テクノロジー</a>
                                <a href="list.php" class="sckw">地域活性化</a>
                                <a href="list.php" class="sckw">映像</a>
                                <a href="list.php" class="sckw">音楽</a>
                                <a href="list.php" class="sckw">フード</a>
                                <a href="list.php" class="sckw">アイドル</a>
                                <a href="list.php" class="sckw">テクノロジー</a>
                                <a href="list.php" class="sckw">地域活性化</a>
                                <a href="list.php" class="sckw">映像</a>
                            </div>
                        </div>
                        <div class="side_content">
                            <h3 class="sc_ttl">
                                ランキング
                            </h3>
                            <div class="sc_inner_rk">
                                <a href="project.php" class="side_ranking">
                                    <div class="sr_num">1</div>
                                    <div class="sr_thum">
                                        <img src="/asset/image/test/11.jpg">
                                    </div>
                                    <div class="sr_ttl">
                                        プロジェクトのタイトルが入りますプロジェクトのタイトルが入ります
                                    </div>
                                </a>
                                <a href="project.php" class="side_ranking">
                                    <div class="sr_num">2</div>
                                    <div class="sr_thum">
                                        <img src="/asset/image/test/14.jpg">
                                    </div>
                                    <div class="sr_ttl">
                                        プロジェクトのタイトルが入りますプロジェクトのタイトルが入りますプロジェクトのタイトルが入ります
                                    </div>
                                </a>
                                <a href="project.php" class="side_ranking">
                                    <div class="sr_num">3</div>
                                    <div class="sr_thum">
                                        <img src="/asset/image/test/16.jpg">
                                    </div>
                                    <div class="sr_ttl">
                                        プロジェクトのタイトルが入りますプロジェクトのタイトルが入ります
                                    </div>
                                </a>
                                <a href="project.php" class="side_ranking">
                                    <div class="sr_num">4</div>
                                    <div class="sr_thum">
                                        <img src="/asset/image/test/9.jpg">
                                    </div>
                                    <div class="sr_ttl">
                                        プロジェクトのタイトルが入りますプロジェクトのタイトルが入ります
                                    </div>
                                </a>
                                <a href="project.php" class="side_ranking">
                                    <div class="sr_num">5</div>
                                    <div class="sr_thum">
                                        <img src="/asset/image/test/8.jpg">
                                    </div>
                                    <div class="sr_ttl">
                                        プロジェクトのタイトルが入りますプロジェクトのタイトルが入ります
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="side_banner_block">
                            <a href="" class="side_banner">
                                <img src="/asset/image/test/bn.jpg">
                            </a>
                            <a href="" class="side_banner">
                                <img src="/asset/image/test/bn.jpg">
                            </a>
                            <a href="" class="side_banner">
                                <img src="/asset/image/test/bn.jpg">
                            </a>
                        </div>
                    </div>

                </div>
            </section>

            <section class="top_news_wrap">
                <div class="top_news_inner">

                    <div class="sec_top_news">
                        <div class="stn_head">
                            <h3 class="stn_ttl">
                                NEWS
                            </h3>
                            <a href="news.php" class="stn_btn">
                                一覧を見る<span><img src="/asset/image/icon/arrow1.svg"></span>
                            </a>
                        </div>
                        <div class="stn_list">
                            <a href="news_detail.php">
                                <dl>
                                    <dt>
                                        2021.06.21<span>Category</span>
                                    </dt>
                                    <dd>
                                        お知らせのタイトルが入りますお知らせのタイトルが入りますお知らせのタイトルが入ります
                                    </dd>
                                </dl>
                            </a>
                            <a href="news_detail.php">
                                <dl>
                                    <dt>
                                        2021.06.21<span>Category</span>
                                    </dt>
                                    <dd>
                                        お知らせのタイトルが入りますお知らせのタイトルが入りますお知らせのタイトルが入りますお知らせのタイトルが入ります
                                    </dd>
                                </dl>
                            </a>
                            <a href="news_detail.php">
                                <dl>
                                    <dt>
                                        2021.06.21<span>Category</span>
                                    </dt>
                                    <dd>
                                        お知らせのタイトルが入りますお知らせのタイトルが入りますお知らせのタイトルが入ります
                                    </dd>
                                </dl>
                            </a>
                            <a href="news_detail.php">
                                <dl>
                                    <dt>
                                        2021.06.21<span>Category</span>
                                    </dt>
                                    <dd>
                                        お知らせのタイトルが入りますお知らせのタイトルが入りますお知らせのタイトルが入ります
                                    </dd>
                                </dl>
                            </a>

                        </div>
                    </div>

                </div>
            </section>

		</div>
    <?php //echo $this->element('base/social_btn')?>
<!--</div>-->

<?php $this->start('script') ?>
<script>
    $(document).ready(function () {
        top_box_position();
        grid_position();
        top_report_position();
    });

    function top_box_position() {
        <?php if($setting['top_box_content_num'] == 1):?>
        $('.content1').css('display', 'table-cell');
        $('.content1').css('padding-bottom', '0px');
        $('.content1').width('100%');
        <?php else:?>
        if ($(window).width() > 780) {
            $('.content1').css('display', 'table-cell');
            $('.content1').css('padding-bottom', '0px');
            $('.content2').css('display', 'table-cell');
            $('.content2').css('padding-top', '40px');
            $('.content2').css('padding-bottom', '0px');
            $('.content1').width('50%');
            $('.content2').width('50%');
        } else {
            $('.content1').css('display', 'block');
            $('.content1').css('padding-bottom', '0');
            $('.content2').css('display', 'block');
            $('.content2').css('padding-top', '10px');
            $('.content2').css('padding-bottom', '25px');
            $('.content1').width('100%');
            $('.content2').width('100%');
        }
        <?php endif?>

        resize_movie(350);
        resize_img();
    }

    function resize_img() {
        $('.top_box_img:visible').css('max-width', '95%');
        if ($('.top_box_img:visible').width() > 350) $('.top_box_img:visible').width(350);
    }
</script>
<?php $this->end() ?>
