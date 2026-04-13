<div class="bread">
    <?php echo $this->Html->link('カテゴリー', '/admin/admin_categories/setting') ?> &gt;
    カテゴリー基本設定
</div>
<div class="setting_title">
    <h2>カテゴリー基本設定</h2>
</div>

<div class="container">
    <br>
    <ul class="nav nav-tabs">
        <li class="active"><a href="<?php echo $this->Html->url('/admin/admin_categories/setting')?>">基本設定</a></li>
        <li><a href="<?php echo $this->Html->url('/admin/admin_categories/') ?>">カテゴリ1</a></li>
        <li><a href="<?php echo $this->Html->url('/admin/admin_categories/cat2')?>">カテゴリ2</a></li>
    </ul>
    <br>

    <?php echo $this->Form->create('Setting', array('inputDefaults' => array('class' => 'form-control')))?>
    <div class="form-group">
        <?php echo $this->Form->input('cat_type_num', array('type' => 'select', 'options' => array(1 => '1つ', 2 => '2つ'), 'label' => '利用するカテゴリ種類の数'))?>
    </div>
    <div class="form-group">
        <?php echo $this->Form->input('cat1_name', array('label' => 'カテゴリ1の名称'))?>
    </div>
    <div class="form-group">
        <?php echo $this->Form->input('cat2_name', array('label' => 'カテゴリ2の名称'))?>
    </div>
    <button type="submit" class="btn btn-primary">更新</button>
    <?php echo $this->Form->end()?>
</div>

