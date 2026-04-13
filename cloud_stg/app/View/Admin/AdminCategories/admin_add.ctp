<div class="bread">
    <?php echo $this->Html->link('カテゴリー', '/admin/admin_categories/setting') ?> &gt;
    <?php echo $this->Html->link('カテゴリー1 一覧', '/admin/admin_categories')?> >
    カテゴリー1 追加
</div>
<div class="setting_title">
    <h2>カテゴリー1の追加</h2>
</div>

<div class="container">
    <br>
    <?php echo $this->Form->create('Category') ?>
    <div class="form-group">
        <?php echo $this->Form->input('name', array(
                'class' => 'form-control', 'label' => 'カテゴリー名'
        )) ?>
    </div>
    <div class="form-group" style="text-align:center;">
        <button type="submit" class="btn btn-primary" style="width:200px;">登録</button>
    </div>
    <?php echo $this->Form->end() ?>
</div>
