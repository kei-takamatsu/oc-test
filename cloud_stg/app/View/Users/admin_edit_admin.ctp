<br>
<ol class="breadcrumb">
    <li><a href="<?php echo $this->Html->url('/admin/users/mypage') ?>">管理トップ</a></li>
    <li><a href="<?php echo $this->Html->url('/admin/users/admins') ?>">管理者一覧</a></li>
    <li class="active">管理者の編集</li>
</ol>

<h2>管理者の編集</h2>

<?php echo $this->Form->create('User', array('inputDefaults' => array('class' => 'form-control'))); ?>

<div class="form-group">
    <?php echo $this->Form->input('nick_name', array(
            'required' => 'required', 'label' => 'ユーザ名'
    )); ?>
</div>

<div class="form-group">
    <?php echo $this->Form->input('email', array(
            'required' => 'required', 'label' => 'メールアドレス'
    )); ?>
</div>

<div class="form-group">
    <?php echo $this->Form->input('password', array(
            'label' => 'パスワード', 'value' => '', 'required' => false
    )); ?>
    <span style="color:blue;">※パスワードを変更する場合のみ入力してください。</span>
</div>

<div class="form-group">
    <?php echo $this->Form->input('password2', array(
            'label' => 'パスワード（確認）', 'type' => 'password'
    )); ?>
</div>

<div class="form-group">
    <?php echo $this->Form->submit(__('更新'), array('class' => 'btn btn-success col-xs-4')); ?>
</div>
<?php echo $this->Form->hidden('group_id', array('value' => '1')); ?>
<?php echo $this->Form->end(); ?>

<br><br><br><br>