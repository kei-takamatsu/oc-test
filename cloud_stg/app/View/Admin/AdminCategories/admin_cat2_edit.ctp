<div class="bread">
    <?php echo $this->Html->link('カテゴリー', '/admin/admin_categories/setting') ?> &gt;
    <?php echo $this->Html->link('カテゴリー2 一覧', '/admin/admin_categories/cat2')?> >
    カテゴリー2 編集
</div>
<div class="setting_title">
    <h2>カテゴリー2の編集</h2>
</div>
<div class="container">
    <br>
    <?php echo $this->Form->create('Area') ?>
    <div class="form-group">
        <?php echo $this->Form->input('name', array(
            'class' => 'form-control', 'label' => 'カテゴリー名'
        )) ?>
    </div>
    <div class="form-group">
        <?php echo $this->Form->input('order', array(
            'class' => 'form-control', 'label' => '並び順（小さい程上に表示）'
        )) ?>
    </div>
    <div class="form-group" style="text-align:center;">
        <button type="submit" class="btn btn-primary" style="width:200px;">登録</button>
    </div>
    <?php echo $this->Form->end() ?>
</div>
