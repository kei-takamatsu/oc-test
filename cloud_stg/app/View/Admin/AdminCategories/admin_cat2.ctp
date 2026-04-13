<div class="bread">
    <?php echo $this->Html->link('カテゴリー', '/admin/admin_categories/setting') ?> &gt;
    カテゴリー2一覧
</div>
<div class="setting_title">
    <h2>カテゴリー2 一覧</h2>
</div>

<div class="container">
    <br>
    <ul class="nav nav-tabs">
        <li><a href="<?php echo $this->Html->url('/admin/admin_categories/setting')?>">基本設定</a></li>
        <li><a href="<?php echo $this->Html->url('/admin/admin_categories/') ?>">カテゴリ1</a></li>
        <li class="active"><a href="<?php echo $this->Html->url('/admin/admin_categories/cat2')?>">カテゴリ2</a></li>
    </ul>
    <br>
<?php if(empty($areas)): ?>
    <div>
        <p>まだカテゴリー2が登録されていません。</p>
        <a href="<?php echo $this->Html->url('/admin/admin_categories/cat2_add') ?>" class="btn btn-primary">
            カテゴリー2の追加
        </a>
    </div>
<?php else: ?>
        <br>
        <div>
            <a href="<?php echo $this->Html->url('/admin/admin_categories/cat2_add') ?>" class="btn btn-primary">
                カテゴリー2の追加
            </a>
        </div>
        <br>

        <table class="table table-bordered">
            <?php foreach($areas as $a): ?>
                <tr>
                    <td>
                        <?php echo h($a['Area']['name']) ?>
                    </td>
                    <td style="width:150px; text-align:center;">
                        <a href="<?php echo $this->Html->url('/admin/admin_categories/cat2_edit/'
                                                             .h($a['Area']['id'])) ?>" class="btn btn-info btn-sm">編集</a>
                        <?php
                        echo $this->Form->postLink('<button class="btn btn-danger btn-sm">削除</button>', '/admin/admin_categories/cat2_delete/'
                                                                                                        .$a['Area']['id'], array(
                                'class' => '', 'escape' => false
                        ), '削除しますか？');
                        ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
<?php endif; ?>

</div>


