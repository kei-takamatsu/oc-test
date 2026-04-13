<?php echo $this->Html->css('grid', null, array('inline' => false)) ?>
<?php echo $this->Html->script('grid_position', array('inline' => false)) ?>

<div class="bread">
    <?php echo $this->Html->link('プロジェクト', '/admin/admin_projects/') ?> &gt;
    支援の管理
</div>
<div class="setting_title">
    <h2>支援の管理</h2>
</div>
<?php echo $this->element('admin/setting_project_main_menu', array('mode' => 'back')) ?>

<div class="container">
    <br>
    <ul class="nav nav-tabs nav-justified">
        <li class="<?php if($mode != 'success'){
            echo 'active';
        } ?>">
            <a href="<?php echo $this->Html->url('/admin/admin_projects/open_projects') ?>">募集中プロジェクト</a>
        </li>
        <li class="<?php if($mode == 'success'){
            echo 'active';
        } ?>">
            <a href="<?php echo $this->Html->url('/admin/admin_projects/open_projects/success') ?>">成功プロジェクト</a>
        </li>
    </ul>
    <br>
    <h2><?php echo h($title) ?>一覧</h2>

    <?php if(!empty($projects)): ?>
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <th>タイトル</th>
                <th>募集終了</th>
                <th>目標金額</th>
                <th>現在金額</th>
                <th>支援者</th>
                <th></th>
            </tr>
            <?php foreach($projects as $p): ?>
                <tr>
                    <td><?php echo h($p['Project']['id']) ?></td>
                    <td>
                        <a href="<?php echo $this->Html->url('/projects/view/'.$p['Project']['id']) ?>" target="_blank">
                            <?php echo h($p['Project']['project_name']) ?>
                        </a>
                    </td>
                    <td><?php echo date('Y/m/d', strtotime(h($p['Project']['collection_end_date']))) ?></td>
                    <td><?php echo '¥'.number_format(h($p['Project']['goal_amount'])) ?></td>
                    <td><?php echo '¥'.number_format(h($p['Project']['collected_amount'])) ?></td>
                    <td><?php echo number_format(h($p['Project']['backers'])) ?>人</td>
                    <td>
                        <a href="<?php echo $this->Html->url('/admin/admin_projects/backers/'.$p['Project']['id']) ?>"
                           class="btn btn-success btn-sm">支援者一覧
                        </a>
                        <?php if($mode != 'success'):?>
                        <a href="<?php echo $this->Html->url('/admin/admin_backed_projects/add_back/'.$p['Project']['id'])?>" class="btn btn-primary btn-sm">
                            支援を追加
                        </a>
                        <?php endif;?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->element('base/pagination') ?>
    <?php endif; ?>
</div>




