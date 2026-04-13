<div id="wrap">
    <div id="header">
        <div class="top_menu">
            <div id="logo">
                管理画面
            </div>
            <div class="left">

            </div>
            <div class="right">
                <?php if($auth_user): ?>
                    <a href="<?php echo $this->Html->url('/admin/logout') ?>">
                        <span class="el-icon-off"></span>
                    </a>
                <?php else: ?>
                    <a href="<?php echo $this->Html->url('/login') ?>" class="btn btn-white btn-sm">
                        ログイン
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php echo $this->element('admin/setting_header'); ?>
    </div>

    <div id="contents">


