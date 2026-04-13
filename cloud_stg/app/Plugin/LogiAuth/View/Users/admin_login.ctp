<div class="container">
    <h2>ログインaaaaaaaaaaaaa</h2>

    <div class="col-md-6">
        <?php echo $this->Form->create('User', array(
                'inputDefaults' => array(
                        'label' => false, 'div' => false, 'class' => 'form-control'
                )
        )) ?>
        <div class="form-group">
            <?php echo $this->Form->input('email', array(
                    'placeholder' => 'メールアドレス', 'value' => ''
            )) ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->input('password', array(
                    'placeholder' => 'パスワード', 'value' => ''
            )) ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->submit('ログイン', array('class' => 'btn btn-primary form-control')) ?>
        </div>
        <?php echo $this->Form->end() ?>
        <br>
        <?php if(!empty($account_lock)): ?>
            <?php echo $this->Html->link('アカウントロック解除はこちら', '/reset_account_lock') ?><br>
        <?php endif; ?>
        <?php echo $this->Html->link('パスワードを忘れた方はこちら', '/forgot_pass') ?>
        <br>
        <br><br>
    </div>
</div>
<br><br>




