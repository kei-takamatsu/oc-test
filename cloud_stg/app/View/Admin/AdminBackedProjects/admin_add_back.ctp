<?php echo $this->Html->css('grid', null, array('inline' => false)) ?>
<?php echo $this->Html->script('grid_position', array('inline' => false)) ?>

<div class="bread">
    <?php echo $this->Html->link('プロジェクト', '/admin/admin_projects/') ?> &gt;
    <?php echo $this->Html->link('支援の管理', '/admin/admin_projects/open_projects')?>  >
    支援の追加
</div>
<div class="setting_title">
    <h2>『<?php echo h($pj['project_name']) ?>』の支援の追加</h2>
</div>
<?php echo $this->element('admin/setting_project_main_menu', array('mode' => 'back')) ?>

<div class="container">
    <br><br>
    <p>追加する支援パターンと支援金額を入力してください。ここで登録する度に支援者人数も1人増加します。<br>
    登録されている支援パターンの確認は
        <?php echo $this->Html->link('こちら', '/admin/admin_projects/edit_level/'.h($pj['id']), array('target' => '_blank'))?>。
    </p>
    <br>
    <div class="col-md-6">
        <?php echo $this->Form->create('BackedProject', array('id' => 'form1', 'inputDefaults' => array('class' => 'form-control')))?>
        <div class="form-group">
            <?php echo $this->Form->input('backing_level_id', array('type' => 'select', 'options' => $levels))?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->input('invest_amount', array('label' => '支援金額'))?>
        </div>
        <div class="form-group">
            <button type="button" onclick="if(window.confirm('登録しますか？')){$('#form1').submit();}" class="btn btn-primary">
                登録
            </button>
        </div>
        <?php echo $this->Form->end()?>
    </div>
</div>




