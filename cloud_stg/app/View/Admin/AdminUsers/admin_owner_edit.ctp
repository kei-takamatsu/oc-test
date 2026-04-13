<div class="bread">
    <?php echo $this->Html->link('アカウント', '/admin/admin_users/owner_edit') ?> &gt;
    アカウント情報
</div>
<div class="setting_title">
    <h2>アカウント情報の編集</h2>
</div>

<div class="container">
    <div style="padding:10px;">
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
            <span style="color:#069;">※パスワードを変更する場合のみ入力してください。</span>
        </div>

        <div class="form-group">
            <div class="col-xs-6 col-xs-offset-3">
                <?php echo $this->Form->submit(__('更新'), array('class' => 'btn btn-primary btn-block')); ?>
            </div>
        </div>
        <br><br>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
