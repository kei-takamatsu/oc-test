<div class="bread">
    <?php echo $this->Html->link('カテゴリー', '/admin/admin_categories/setting') ?> &gt;
    カテゴリー1 一覧
</div>
<div class="setting_title">
    <h2>カテゴリー1 一覧</h2>
</div>
<div class="container">
    <br>
    <ul class="nav nav-tabs">
        <li><a href="<?php echo $this->Html->url('/admin/admin_categories/setting')?>">基本設定</a></li>
        <li class="active"><a href="<?php echo $this->Html->url('/admin/admin_categories/') ?>">カテゴリ1</a></li>
        <li><a href="<?php echo $this->Html->url('/admin/admin_categories/cat2')?>">カテゴリ2</a></li>
    </ul>
    <br>
<?php if(empty($categories)): ?>
    <div>
        <p>まだカテゴリー1が登録されていません。</p>
        <a href="<?php echo $this->Html->url('/admin/admin_categories/add') ?>" class="btn btn-primary">
            カテゴリー1の追加
        </a>
    </div>
<?php else: ?>
        <br>
        <div>
            <a href="<?php echo $this->Html->url('/admin/admin_categories/add') ?>" class="btn btn-primary">
                カテゴリー1の追加
            </a>
        </div>
        <br>

        <table class="table table-bordered">
            <?php foreach($categories as $c): ?>
                <tr>
                    <td>
                        <?php echo h($c['Category']['name']) ?>
                    </td>
                    <td style="width:150px; text-align:center;">
                        <a href="<?php echo $this->Html->url('/admin/admin_categories/edit/'
                                                             .h($c['Category']['id'])) ?>" class="btn btn-info btn-sm">編集</a>
                        <?php
                        echo $this->Form->postLink('<button class="btn btn-danger btn-sm">削除</button>', '/admin/admin_categories/delete/'
                                                                                                        .$c['Category']['id'], array(
                                'class' => '', 'escape' => false
                        ), '削除しますか？');
                        ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
<?php endif; ?>
</div>



