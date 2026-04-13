<div class="top_padding"></div>
<div class="header_content clearfix">

    <div class="menu">
        <div class="mypage_menu" onclick="location.href='<?php echo $this->Html->url('/mypage') ?>';">
            <span class="el-icon-home"></span>
        </div>
        <div class="mypage_menu" onclick="location.href='<?php echo $this->Html->url('/users/edit') ?>';">
            <span class="el-icon-adult"></span>
        </div>
        <div class="mypage_menu" onclick="location.href='<?php echo $this->Html->url('/messages') ?>';">
            <span class="el-icon-envelope"></span>
        </div>
        <div class="mypage_menu" onclick="location.href='<?php echo $this->Html->url('/social') ?>';">
            <span class="el-icon-facebook"></span>
        </div>

        <div class="mypage_menu" onclick="location.href='<?php echo $this->Html->url('/favourite_projects') ?>';">
            <span class="el-icon-heart"></span>
        </div>
        <div class="mypage_menu" onclick="location.href='<?php echo $this->Html->url('/comments') ?>';">
            <span class="el-icon-comment"></span>
        </div>
    </div>
</div>
<div class="user_info">
    <div>
        <?php echo $this->User->get_user_img_md($auth_user, 24) ?>
    </div>
    <div class="nick_name">
        <?php echo h($auth_user['User']['nick_name']) ?>
    </div>
    <div class="profile_btn">
        <button onclick="location.href='<?php echo $this->Html->url('/profile/'.$auth_user['User']['id']) ?>';"
                class="btn btn-danger btn-xs">プロフィール確認
        </button>
    </div>
</div>