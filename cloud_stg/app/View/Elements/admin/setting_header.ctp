<p class="setting_menu">
    <?php echo $this->Html->link('プロジェクト', '/admin/admin_projects') ?>&nbsp;|&nbsp;
    <?php echo $this->Html->link('ユーザー', '/admin/admin_users') ?>&nbsp;|&nbsp;
    <?php echo $this->Html->link('基本設定', '/admin/admin_settings/edit_info') ?>&nbsp;|&nbsp;
    <?php echo $this->Html->link('デザイン', '/admin/admin_design/edit') ?>&nbsp;|&nbsp;
    <?php echo $this->Html->link('カテゴリー', '/admin/admin_categories/setting') ?>&nbsp;|&nbsp;
    <?php echo $this->Html->link('アカウント', '/admin/admin_users/owner_edit') ?>&nbsp;|&nbsp;
    <?php echo $this->Html->link('サイトを開く', '/', array('target' => '_blank')) ?>
</p>
<?php if(!empty($setting_title)): ?>
    <h1>
        <?php echo h($setting_title) ?>
    </h1>
<?php endif; ?>