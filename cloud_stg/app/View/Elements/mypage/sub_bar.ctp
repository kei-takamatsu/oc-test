<div class="profile" onclick="location.href='<?php echo $this->Html->url('/profile/'.$auth_user['User']['id']) ?>'">
    プロフィール確認
</div>
<div class="mypage_menu" onclick="location.href='<?php echo $this->Html->url('/mypage') ?>'">
    <span class="el-icon-gift"></span> 支援したプロジェクト
</div>

<div class="mypage_menu" onclick="location.href='<?php echo $this->Html->url('/projects/registered') ?>'">
    <span class="el-icon-file-new"></span> 作成したプロジェクト
</div>
<div class="mypage_menu" onclick="location.href='<?php echo $this->Html->url('/reports') ?>'">
    <span class="el-icon-bullhorn"></span> 活動報告
</div>
<div class="mypage_menu" onclick="location.href='<?php echo $this->Html->url('/users/edit') ?>'">
    <span class="el-icon-adult"></span> アカウント情報
</div>
<div class="mypage_menu" onclick="location.href='<?php echo $this->Html->url('/messages') ?>'">
    <span class="el-icon-envelope"></span> メッセージ
</div>
<div class="mypage_menu" onclick="location.href='<?php echo $this->Html->url('/banks/index') ?>'">
    <span style="font-weight:bold;">￥</span> 入金関連
</div>
<div class="mypage_menu" onclick="location.href='<?php echo $this->Html->url('/social') ?>'">
    <span class="el-icon-facebook"></span> ソーシャル連携
</div>

<div class="mypage_menu" onclick="location.href='<?php echo $this->Html->url('/favourite_projects') ?>'">
    <span class="el-icon-heart"></span> お気に入り
</div>
<div class="mypage_menu">
    <a href="<?php echo $this->Html->url('/comments') ?>">
        <span class="el-icon-comment"></span> コメント一覧
    </a>
</div>