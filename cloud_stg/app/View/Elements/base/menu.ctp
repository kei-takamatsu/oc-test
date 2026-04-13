<!--
<div id="wrap">
    <div id="menu_bar" class="<?php echo !empty($box_top) ? 'menu_bar_top' : '' ?>">
        <div id="brand">
            <a href="<?php echo $this->Html->url('/') ?>">
                <?php
                if(!empty($setting['logo'])){
                    echo $this->Label->image($setting['logo'], array('id' => 'logo'));
                }else{
                    echo h($setting['site_name']);
                }
                ?>
            </a>
        </div>
        <div class="menu">
            <a href="<?php echo $this->Html->url('/projects') ?>">
                <span class="el-icon-search"></span>
                <span class="hidden-sm hidden-xs">プロジェクトを</span>探す
            </a>
            <a href="<?php echo $this->Html->url('/make') ?>">
                <span class="el-icon-file-new"></span>
                <span class="hidden-sm hidden-xs">プロジェクトを</span>作る
            </a>
            <a href="<?php echo $this->Html->url('/about') ?>">
                <span class="el-icon-question"></span>
                <?php echo h($setting['site_name']) ?>とは

            </a>
        </div>

        <div class="menu_right">
            <?php if($auth_user): ?>
                <div class="user">
                    <a href="<?php echo $this->Html->url('/mypage') ?>">
                        <?php echo $this->User->get_user_img_sm($auth_user) ?>&nbsp;
                        <?php echo h($auth_user['User']['nick_name']) ?>
                    </a>
                </div>
                <div class="logout">
                    <a href="<?php echo $this->Html->url('/logout') ?>">
                        <span class="el-icon-off"></span>
                    </a>
                </div>
            <?php else: ?>
                <div>
                    <?php echo $this->Html->link('ログイン', '/login') ?>
                </div>
                <div>
                    <?php echo $this->Html->link('新規登録', '/mail_auth') ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="icon-bar" href="icon-bar" onclick="toggle_sub_menu();">
            <span class="el-icon-lines"></span>
        </div>

        <div id="sub_menu">
            <div>
                <a href="<?php echo $this->Html->url('/projects') ?>">
                    <span class="el-icon-search"></span>
                    探す
                </a>
            </div>
            <div>
                <a href="<?php echo $this->Html->url('/make') ?>">
                    <span class="el-icon-file-new"></span>
                    作る
                </a>
            </div>
            <div>
                <a href="<?php echo $this->Html->url('/about') ?>">
                    <span class="el-icon-question"></span>
                    <?php echo h($setting['site_name']) ?>?
                </a>
            </div>
            <?php if($auth_user): ?>
                <div>
                    <a href="<?php echo $this->Html->url('/mypage') ?>">
                        <?php echo $this->User->get_user_img_sm($auth_user) ?>
                        <?php echo h($auth_user['User']['nick_name']) ?>
                    </a>
                </div>
                <div>
                    <a href="<?php echo $this->Html->url('/logout') ?>">
                        <span class="el-icon-off"></span> ログアウト
                    </a>
                </div>
            <?php else: ?>
                <div>
                    <?php echo $this->Html->link('ログイン', '/login') ?>
                </div>
                <div>
                    <?php echo $this->Html->link('新規登録', '/mail_auth') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="contents">
-->


<!-- sp menu -->
        <div class="fullscreenmenu">
            <div id="nav2">
                <div class="modal_menu_wrap">
                    <div class="modal_menu_logo">
                <?php
                if(!empty($setting['logo'])){
                    echo $this->Label->image($setting['logo'], array('id' => 'logo'));
                }else{
                    echo h($setting['site_name']);
                }
                ?>
                        <img src="/asset/image/logo.svg">
                    </div>
                    <ul>
                        <a href="<?php echo $this->Html->url('/') ?>">TOP</a></li>
                        <li><a href="company">会社概要</a></li>
                        <li><a href="service.php">サービス紹介</a></li>
                        <li><a href="people.php">社員紹介</a></li>                           
                        <li><a href="recruit.php">採用情報</a></li>
                        <li><a href="news.php">お知らせ</a></li>
                    </ul>
                    <div class="modal_menu_entry">
                        <a href="contact.php" class="regist_mdl">
                            <span>お問い合わせ</span>
                        </a>
                    </div>
                </div>        
            </div>
            <button class="menu2"><span></span><span></span><span></span></button>
        </div>
        <!-- End sp menu -->

        <header id="header">
            <a href="./" class="header_logo">
                <h1 class="hl_main">
                    <img src="/asset/image/logo_b.svg">
                </h1>
                <h1 class="hl_main_sp">
                    <img src="/asset/image/logo.svg">
                </h1>
                <div class="hl_tx">
                    <img src="/asset/image/logo_tx.svg">
                </div>
                <div class="hl_tx_sp">
                    <img src="/asset/image/logo_tx.png">
                </div>
            </a>
            <div class="header_navi">
                <div class="hn_search">
                    <form id="hn_form" action="list.php">
                        <input id="hn_input" type="text" placeholder="keyword">
                        <input id="hn_submit" type="submit" value="">
                    </form>
                </div>
                <div class="hn_cont">
                    <ul class="hn_txtnav">
                        <li>
                            <a href="about.php">〇〇〇とは</a>
                        </li>
                        <li>
                            <a href="guide.php">ご利用ガイド</a>
                        </li>
                    </ul>

                    <?php if($auth_user): ?>
                    <div class="after_login">
                        <a href="" class="al_mail">
                            <img src="/asset/image/icon/mail.svg">
                        </a>
                        <a href="" class="al_user">
                            <img src="/asset/image/test/user_1.jpg">
                        </a>
                    </div> 

                    
                    <div class="after_login_sp">
                        <a href="" class="al_mail_sp">
                            <img src="/asset/image/icon/mail_w.svg">
                        </a>
                        <a href="" class="al_user_sp">
                            <img src="/asset/image/test/user_1.jpg">
                        </a>
                    </div> 

                    <?php else: ?>
                    <!-- 未ログイン PC -->
                    <ul class="hn_iconav">
                        <li>
                            <a href="<?php echo $this->Html->url('/login') ?>"><img src="/asset/image/icon/login.svg"></a>
                        </li>
                        <li>
                            <a href="<?php echo $this->Html->url('/mail_auth') ?>"><img src="/asset/image/icon/regist.svg"></a>
                        </li>
                    </ul>
                    <!-- 未ログイン SP -->
                    <ul class="hn_iconav_sp">
                        <li>
                            <a href="<?php echo $this->Html->url('/login') ?>"><img src="/asset/image/icon/login_w.svg"></a>
                        </li>
                        <li>
                            <a href="<?php echo $this->Html->url('/mail_auth') ?>"><img src="/asset/image/icon/regist_w.svg"></a>
                        </li>
                    </ul>

                    <?php endif; ?>
                </div>
            </div>
        </header>
