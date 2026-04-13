<div class="setting_project_main_menu">
    <div class="<?php echo ($mode == 'index') ? 'active' : '' ?>"
         onclick="location.href='<?php echo $this->Html->url('/admin/admin_projects/index') ?>'">
        <span class="hidden-xs">プロジェクト</span>一覧
    </div>
    <div class="<?php echo ($mode == 'create') ? 'active' : '' ?>"
         onclick="location.href='<?php echo $this->Html->url('/admin/admin_projects/create') ?>'">
        <span class="hidden-xs">プロジェクト</span>作成
    </div>
    <div class="<?php echo ($mode == 'toppage') ? 'active' : '' ?>"
         onclick="location.href='<?php echo $this->Html->url('/admin/admin_projects/toppage') ?>'">
        <span class="hidden-xs">トップページ</span>表示
    </div>
    <div class="<?php echo ($mode == 'back') ? 'active' : '' ?>"
         onclick="location.href='<?php echo $this->Html->url('/admin/admin_projects/open_projects') ?>'">
        支援<span class="hidden-xs">の管理</span>
    </div>
    <div class="<?php echo ($mode == 'comments') ? 'active' : '' ?>"
         onclick="location.href='<?php echo $this->Html->url('/admin/admin_comments') ?>'">
        コメント
    </div>
</div>