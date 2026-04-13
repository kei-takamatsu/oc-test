<div class="setting_project_menu clearfix">
    <div class="menu <?php echo ($mode == 'base') ? 'active' : '' ?>"
         onclick="location.href='<?php echo $this->Html->url('/admin/admin_projects/edit/'
                                                             .$project['Project']['id']) ?>'">
        基本情報
    </div>
    <div class="menu <?php echo ($mode == 'detail') ? 'active' : '' ?>"
         onclick="location.href='<?php echo $this->Html->url('/admin/admin_projects/edit_detail/'
                                                             .$project['Project']['id']) ?>'">
        詳細
    </div>
    <div class="menu <?php echo ($mode == 'thumb') ? 'active' : '' ?>"
         onclick="location.href='<?php echo $this->Html->url('/admin/admin_projects/edit_thumb/'
                                                             .$project['Project']['id']) ?>'">
        サムネイル
    </div>
    <div class="menu <?php echo ($mode == 'back') ? 'active' : '' ?>"
         onclick="location.href='<?php echo $this->Html->url('/admin/admin_projects/edit_level/'
                                                             .$project['Project']['id']) ?>'">
        支援パターン
    </div>
</div>
