
<!--
<div id="back_top" onclick="back_top();">
    <?php echo $this->Html->image('back_top.png') ?>
</div>
<div class="footer footer_pc hidden-xs">
    <div class="menu clearfix">
        <?php echo $this->Html->link('お問い合わせ', '/contact') ?>　｜　
        <?php echo $this->Html->link('利用規約', '/rule') ?>　｜　
        <?php echo $this->Html->link('特定商取引法に基づく表記', '/tokutei') ?>　｜　
        <?php echo $this->Html->link('プライバシーポリシー', '/policy') ?>　｜　
        <?php echo $this->Html->link('運営会社', h($setting['company_url']), array('target' => '_blank')) ?>
    </div>

    <div class="copyright">
        © 2014 <?php echo h($setting['copy_right']) ?>
    </div>
</div>
<div class="footer footer_sp visible-xs">
    <div class="btn_sp" onclick="footer_menu();">
        <span class="el-icon-lines"></span>
    </div>
    <div class="menu_sp">
        <div onclick="location.href='<?php echo $this->Html->url('/contact') ?>'">
            お問い合わせ
        </div>
        <div onclick="location.href='<?php echo $this->Html->url('/rule') ?>'">
            利用規約
        </div>
        <div onclick="location.href='<?php echo $this->Html->url('/tokutei') ?>'">
            特定商取引法
        </div>
        <div onclick="location.href='<?php echo $this->Html->url('/policy') ?>'">
            プライバシーポリシー
        </div>
        <div onclick="window.open('<?php echo $this->Html->url(h($setting['company_url'])) ?>', '_blank');">
            運営会社
        </div>
        <div>
            © 2014 <?php echo h($setting['copy_right']) ?>
        </div>
    </div>
</div>
</div>

<div id="loader" style="display: none;">
    <div id="loader_content">
        <?php echo $this->Html->image('loader.gif') ?>
    </div>
</div>
-->
<footer id="footer">
            <div class="footer_inner">
                <div class="footer_logo_wrap">
                    <a href="<?php echo $this->Html->url('/') ?>" class="footer_logo">
                        <img src="/asset/image/logo.svg">
                    </a>
                </div>       
                
                <div class="sec_footer_menu">
                    <ul class="footer_menu">
                        <li>
                            <a href="<?php echo $this->Html->url('/about') ?>"><?php echo h($setting['site_name']) ?>とは？</a>
                        </li>
                        <li>
                            <a href="guide.php">ご利用ガイド</a>
                        </li>
                        <li>
                            <a href="faq.php">よくあるご質問</a>
                        </li>
                        <li>
                            <a href="news.php">お知らせ</a>
                        </li>
                        <li>
                            <?php echo $this->Html->link('お問い合わせ', '/contact') ?>
                        </li>
                    </ul>
                    <ul class="footer_menu">
                        <li>
                            <?php echo $this->Html->link('利用規約', '/rule') ?>
                        </li>
                        <li>
                            <?php echo $this->Html->link('プライバシーポリシー', '/policy') ?>
                        </li>
                        <li>
                            <?php echo $this->Html->link('特定商取引法に基づく表記', '/tokutei') ?>
                        </li>
                        <li>
                            <a href="requirement.php">推奨環境</a>
                        </li>
                        <li>
                            <a href="">運営会社</a>
                        </li>
                    </ul>
                    <ul class="footer_menu">
                        <li>
                            <a href="">カテゴリー</a>
                        </li>
                        <li>
                            <a href="">カテゴリー</a>
                        </li>
                        <li>
                            <a href="">カテゴリー</a>
                        </li>
                        <li>
                            <a href="">カテゴリー</a>
                        </li>
                        <li>
                            <a href="">カテゴリー</a>
                        </li>
                    </ul>
                </div>                
            </div>
            <div class="footer_sub">
                <div class="footer_sns">
                    <a href="" class="fs_ico">
                        <img src="/asset/image/icon/instagram_g.svg">
                    </a>
                    <a href="" class="fs_ico">
                        <img src="/asset/image/icon/facebook_g.svg">
                    </a>
                    <a href="" class="fs_ico">
                        <img src="/asset/image/icon/twitter_g.svg">
                    </a>
                </div>
                <div class="copyright">
                     © 2022 TV TOKYO Direct.Inc
                </div>
            </div>
        </footer>
        <p id="page-top">
            <a href="#wrapper" class="ubbs__2">
                <img src="/asset/image/icon/arrow_up.png">
            </a>
        </p>
<?php echo $this->Html->script('jquery-2.1.0.min') ?>
<?php echo $this->Html->script('/bootstrap/js/bootstrap.min') ?>
<?php echo $this->Html->script('anime') ?>
<?php echo $this->element('js/default_js') ?>
<?php echo $this->Html->script('default') ?>
<?php echo $this->fetch('script') ?>
</div>
</body>
</html>